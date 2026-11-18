<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MaxSubscription;
use App\Models\MaxSentMessage;
use App\Models\User;
use App\Service\MaxBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Lid;
use App\Models\BioLid;
use App\Jobs\SyncMaxLidMessages;
use App\Jobs\SyncMaxBioLidMessages;


class MaxBotWebhookController extends Controller
{
    public function __invoke(Request $request, MaxBotService $maxBot): JsonResponse
    {
        $secret = (string) config('services.max.webhook_secret', '');
        $receivedSecret = (string) $request->header('X-Max-Bot-Api-Secret', '');
        if ($secret === '' || !hash_equals($secret, $receivedSecret)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $update = $request->all();
        $type = $update['update_type'] ?? null;
        if (!is_string($type)) {
            return response()->json(['error' => 'Invalid update'], 400);
        }

        // Дедупликация повторно доставленных webhook. В multi-server среде нужен общий Redis.
        $timestamp = $update['timestamp'] ?? null;
        $cacheKey = null;
        if (is_numeric($timestamp)) {
            $cacheKey = 'max:webhook:' . hash('sha256', implode('|', [
                $type,
                (string) $timestamp,
                (string) data_get($update, 'message.body.mid', ''),
                (string) data_get($update, 'message.sender.user_id', data_get($update, 'user.user_id', '')),
                (string) data_get($update, 'chat_id', ''),
            ]));
            if (!Cache::add($cacheKey, true, now()->addDay())) {
                return response()->json(['ok' => true, 'duplicate' => true]);
            }
        }

        try {
            match ($type) {
                'bot_started' => $this->onBotStarted($update, $maxBot),
                'bot_stopped', 'dialog_removed' => $this->onBotStopped($update),
                'message_created' => $this->onMessageCreated($update, $maxBot),
                'message_callback' => $this->onMessageCallback($update, $maxBot),
                default => null,
            };
        } catch (Throwable $e) {
            if ($cacheKey !== null) {
                Cache::forget($cacheKey);
            }
            // Возвращаем ошибку, чтобы MAX мог повторить webhook.
            throw $e;
        }

        return response()->json(['ok' => true]);
    }

    private function onBotStarted(array $update, MaxBotService $maxBot): void
    {
        $maxUserId = data_get($update, 'user.user_id');
        if ($maxUserId === null) {
            Log::warning('MAX: bot_started без user.user_id');
            return;
        }

        $chatId = data_get($update, 'chat_id');
        // Сохраняем chat_id только для уже подтверждённой связки MAX ↔ users.
        if ($chatId !== null) {
            MaxSubscription::query()
                ->where('max_user_id', $maxUserId)
                ->update(['chat_id' => $chatId]);
        }

        $this->requestPhoneIfNeeded($maxUserId, $maxBot);
    }

    private function onBotStopped(array $update): void
    {
        $maxUserId = data_get($update, 'user.user_id');
        $chatId = data_get($update, 'chat_id');

        if ($maxUserId !== null) {
            MaxSubscription::query()
                ->where('max_user_id', $maxUserId)
                ->update(['subscribed_at' => null]);
        } elseif ($chatId !== null) {
            MaxSubscription::query()
                ->where('chat_id', $chatId)
                ->update(['subscribed_at' => null]);
        }
    }

    private function onMessageCreated(array $update, MaxBotService $maxBot): void
    {
        $message = $update['message'] ?? null;
        if (!is_array($message)) {
            return;
        }

        $maxUserId = data_get($message, 'sender.user_id');

        if ($maxUserId === null) {
            return;
        }

// Игнорируем сообщения, отправленные ботами
        if (data_get($message, 'sender.is_bot') === true) {
            return;
        }

        Log::info('MAX: входящее сообщение', [
            'max_user_id' => $maxUserId,
            'chat_id' => data_get($message, 'recipient.chat_id'),
            'message_id' => data_get($message, 'body.mid'),
        ]);

        $chatType = data_get($message, 'recipient.chat_type');
        // Не обрабатываем контакты, присланные в групповой чат или канал.
        if ($chatType !== null && $chatType !== 'dialog') {
            return;
        }

        $chatId = data_get($message, 'recipient.chat_id', data_get($update, 'chat_id'));
        $attachments = data_get($message, 'body.attachments', []);
        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                if (!is_array($attachment) || ($attachment['type'] ?? null) !== 'contact') {
                    continue;
                }

                $contact = $maxBot->extractPhoneFromMaxMessage($message);

                if ($contact === null) {
                    // VCF в webhook и API может отличаться. Запрашиваем каноническое
                    // сообщение и снова проверяем HMAC. Без проверки подписки не будет.
                    $contact = $maxBot->extractVerifiedContactViaApi(
                        $message,
                        $maxUserId,
                        $chatId
                    );
                }

                if ($contact === null ||
                    ($contact['max_user_id'] !== null &&
                        (string) $contact['max_user_id'] !== (string) $maxUserId)) {
                    $maxBot->requestPhone(
                        $maxUserId,
                        'Контакт не подтверждён. Нажмите кнопку, чтобы поделиться вашим номером MAX.'
                    );
                    return;
                }

                $this->subscribe($maxUserId, $chatId, $contact['phone'], $maxBot);
                return;
            }
        }

        $text = mb_strtolower(
            trim((string) data_get($message, 'body.text', ''))
        );

// Отвечаем только на команду запуска
        if (in_array($text, ['/start', 'старт'], true)) {
            $this->requestPhoneIfNeeded($maxUserId, $maxBot);
        }
    }

    private function onMessageCallback(
        array $update,
        MaxBotService $maxBot
    ): void {

        Log::warning('MAX: callback получен', [
            'payload' => data_get($update, 'callback.payload'),
            'callback_id_exists' => !empty(data_get($update, 'callback.callback_id')),
            'max_user_id' => data_get($update, 'callback.user.user_id'),
            'message_id' => data_get($update, 'message.body.mid'),
        ]);


        $payload = data_get($update, 'callback.payload');
        $callbackId = data_get($update, 'callback.callback_id');
        $maxUserId = data_get($update, 'callback.user.user_id');
        $messageId = data_get($update, 'message.body.mid');

        if (!is_string($payload)) {
            return;
        }

        if (preg_match('/^accept_biolid:([1-9][0-9]*)$/D', $payload, $matches)) {
            $this->acceptBioLid(
                (int) $matches[1],
                $callbackId,
                $maxUserId,
                $messageId,
                $maxBot
            );
            return;
        }

        if (!preg_match('/^accept_lid:([1-9][0-9]*)$/D', $payload, $matches)) {
            return;
        }

        $this->acceptLid(
            (int) $matches[1],
            $callbackId,
            $maxUserId,
            $messageId,
            $maxBot
        );
    }

    private function acceptLid(
        int $lidId,
        mixed $callbackId,
        mixed $maxUserId,
        mixed $messageId,
        MaxBotService $maxBot
    ): void {
        if (!$callbackId || !$maxUserId || !$messageId) {
            Log::warning('MAX: некорректный callback заявки');
            return;
        }

        $subscription = MaxSubscription::query()
            ->where('max_user_id', $maxUserId)
            ->active()
            ->first();

        $allowed = $subscription !== null &&
            MaxSentMessage::query()
                ->where('message_id', $messageId)
                ->where('lid_id', $lidId)
                ->where('user_id', $subscription->user_id)
                ->where('max_user_id', $maxUserId)
                ->exists();

        if (!$allowed) {
            Log::warning('MAX: отказ в принятии заявки', [
                'lid_id' => $lidId,
                'max_user_id' => $maxUserId,
                'message_id' => $messageId,
                'subscription_found' => $subscription !== null,
            ]);

            $maxBot->answerCallback(
                $callbackId,
                'Нет доступа к этой заявке.'
            );
            return;
        }

        $user = User::find($subscription->user_id);

        Log::warning('MAX: определение пользователя при принятии заявки', [
            'lid_id' => $lidId,
            'callback_max_user_id' => (string) $maxUserId,
            'subscription_user_id' => $subscription->user_id,
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'responsible_id_before' => Lid::query()
                ->whereKey($lidId)
                ->value('responsible_id'),
        ]);

        Log::warning('MAX: проверка доступа к заявке', [
            'lid_id' => $lidId,
            'user_id' => $user?->id,
            'role' => $user?->role,
            'subscription_active' => $subscription !== null,
            'received_this_message' => $allowed,
        ]);

        if (!$user || !in_array((int) $user->role, [1, 3], true)) {
            $maxBot->answerCallback(
                $callbackId,
                'Недостаточно прав для принятия заявки.'
            );
            return;
        }

        $result = DB::transaction(function () use ($lidId, $user) {

            $lid = Lid::query()
                ->whereKey($lidId)
                ->lockForUpdate()
                ->first();

            if (!$lid) {
                return 'not_found';
            }

            if ($lid->responsible_id !== null) {
                Log::info('MAX: заявка уже принята', [
                    'lid_id' => $lidId,
                    'clicked_user_id' => $user->id,
                    'existing_responsible_id' => $lid->responsible_id,
                ]);

                return 'already_assigned';
            }

            $lid->responsible_id = $user->getKey();
            $lid->save();

            Log::info('MAX: назначен новый ответственный', [
                'lid_id' => $lid->getKey(),
                'responsible_id' => $lid->responsible_id,
                'clicked_user_id' => $user->getKey(),
            ]);

            return 'accepted';
        });

        if ($result === 'not_found') {
            $maxBot->sendToUser($maxUserId, 'Заявка не найдена.');
            return;
        }

        $lid = Lid::find($lidId);

        $responsible = $lid?->responsible_id
            ? User::find($lid->responsible_id)
            : null;

        if (!$responsible) {
            Log::error('MAX: ответственный не найден', [
                'lid_id' => $lidId,
            ]);
            return;
        }

        $sentMessage = MaxSentMessage::query()
            ->where('message_id', $messageId)
            ->where('lid_id', $lidId)
            ->where('user_id', $user->id)
            ->first();

        if (!$sentMessage) {
            Log::warning('MAX: исходное сообщение не найдено', [
                'lid_id' => $lidId,
                'message_id' => $messageId,
            ]);
            return;
        }

        $isResponsible =
            (string) $user->id === (string) $responsible->id;

        $newText = SyncMaxLidMessages::formatMessage(
            (string) $sentMessage->text,
            $isResponsible,
            (string) $responsible->name
        );

        if (mb_strlen($newText) > 4000) {
            Log::error('MAX: сообщение слишком длинное', [
                'lid_id' => $lidId,
                'message_id' => $messageId,
            ]);
            return;
        }

        Log::warning('MAX: начинаем обновление уведомления', [
            'lid_id' => $lidId,
            'message_id' => $messageId,
            'result' => $result,
            'text_length' => mb_strlen($newText),
        ]);

        $this->updateMaxCallbackMessage(
            $maxBot,
            $callbackId,
            $messageId,
            $sentMessage,
            $newText,
            ['lid_id' => $lidId]
        );

        SyncMaxLidMessages::dispatch(
            $lidId,
            (int) $responsible->id
        );

        Log::info('MAX: запущено обновление уведомлений', [
            'lid_id' => $lidId,
            'responsible_id' => $responsible->id,
            'result' => $result,
        ]);
    }

    private function acceptBioLid(
        int $bioLidId,
        mixed $callbackId,
        mixed $maxUserId,
        mixed $messageId,
        MaxBotService $maxBot
    ): void {
        if (!$callbackId || !$maxUserId || !$messageId) {
            Log::warning('MAX: некорректный callback заявки БИО');
            return;
        }

        $subscription = MaxSubscription::query()
            ->where('max_user_id', $maxUserId)
            ->active()
            ->first();

        $allowed = $subscription !== null &&
            MaxSentMessage::query()
                ->where('message_id', $messageId)
                ->where('bio_lid_id', $bioLidId)
                ->where('user_id', $subscription->user_id)
                ->where('max_user_id', $maxUserId)
                ->exists();

        if (!$allowed) {
            Log::warning('MAX: отказ в принятии заявки БИО', [
                'bio_lid_id' => $bioLidId,
                'max_user_id' => $maxUserId,
                'message_id' => $messageId,
                'subscription_found' => $subscription !== null,
            ]);

            $maxBot->answerCallback(
                $callbackId,
                'Нет доступа к этой заявке.'
            );
            return;
        }

        $user = User::find($subscription->user_id);

        if (!$user || !in_array((int) $user->role, [1, 3], true)) {
            $maxBot->answerCallback(
                $callbackId,
                'Недостаточно прав для принятия заявки.'
            );
            return;
        }

        $result = DB::transaction(function () use ($bioLidId, $user) {
            $bioLid = BioLid::query()
                ->whereKey($bioLidId)
                ->lockForUpdate()
                ->first();

            if (!$bioLid) {
                return 'not_found';
            }

            if ($bioLid->responsible_id !== null) {
                Log::info('MAX: заявка БИО уже принята', [
                    'bio_lid_id' => $bioLidId,
                    'clicked_user_id' => $user->id,
                    'existing_responsible_id' => $bioLid->responsible_id,
                ]);

                return 'already_assigned';
            }

            $bioLid->responsible_id = $user->getKey();
            $bioLid->save();

            Log::info('MAX: назначен ответственный за заявку БИО', [
                'bio_lid_id' => $bioLid->getKey(),
                'responsible_id' => $bioLid->responsible_id,
                'clicked_user_id' => $user->getKey(),
            ]);

            return 'accepted';
        });

        if ($result === 'not_found') {
            $maxBot->sendToUser($maxUserId, 'Заявка не найдена.');
            return;
        }

        $bioLid = BioLid::find($bioLidId);

        $responsible = $bioLid?->responsible_id
            ? User::find($bioLid->responsible_id)
            : null;

        if (!$responsible) {
            Log::error('MAX: ответственный за заявку БИО не найден', [
                'bio_lid_id' => $bioLidId,
            ]);
            return;
        }

        $sentMessage = MaxSentMessage::query()
            ->where('message_id', $messageId)
            ->where('bio_lid_id', $bioLidId)
            ->where('user_id', $user->id)
            ->first();

        if (!$sentMessage) {
            Log::warning('MAX: исходное сообщение БИО не найдено', [
                'bio_lid_id' => $bioLidId,
                'message_id' => $messageId,
            ]);
            return;
        }

        $isResponsible =
            (string) $user->id === (string) $responsible->id;

        $newText = SyncMaxLidMessages::formatMessage(
            (string) $sentMessage->text,
            $isResponsible,
            (string) $responsible->name
        );

        if (mb_strlen($newText) > 4000) {
            Log::error('MAX: сообщение БИО слишком длинное', [
                'bio_lid_id' => $bioLidId,
                'message_id' => $messageId,
            ]);
            return;
        }

        $this->updateMaxCallbackMessage(
            $maxBot,
            $callbackId,
            $messageId,
            $sentMessage,
            $newText,
            ['bio_lid_id' => $bioLidId]
        );

        SyncMaxBioLidMessages::dispatch(
            $bioLidId,
            (int) $responsible->id
        );

        Log::info('MAX: запущено обновление уведомлений БИО', [
            'bio_lid_id' => $bioLidId,
            'responsible_id' => $responsible->id,
            'result' => $result,
        ]);
    }

    private function updateMaxCallbackMessage(
        MaxBotService $maxBot,
        mixed $callbackId,
        mixed $messageId,
        MaxSentMessage $sentMessage,
        string $newText,
        array $context
    ): void {
        try {
            $response = $maxBot->answerCallback($callbackId, $newText);

            Log::info('MAX: callback успешно обработан', $context + [
                'message_id' => $messageId,
                'response' => $response,
            ]);

            $sentMessage->update(['text' => $newText]);
        } catch (Throwable $e) {
            Log::error('MAX: ошибка answerCallback', $context + [
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);

            try {
                $response = $maxBot->editMessage(
                    (string) $messageId,
                    $newText
                );

                $sentMessage->update(['text' => $newText]);

                Log::info('MAX: сообщение обновлено резервным способом', $context + [
                    'message_id' => $messageId,
                    'response' => $response,
                ]);
            } catch (Throwable $editException) {
                Log::error('MAX: оба способа редактирования завершились ошибкой', $context + [
                    'message_id' => $messageId,
                    'error' => $editException->getMessage(),
                ]);
            }
        }
    }

    private function requestPhoneIfNeeded(
        int|string $maxUserId,
        MaxBotService $maxBot
    ): void {
        $active = MaxSubscription::query()
            ->where('max_user_id', $maxUserId)
            ->active()
            ->exists();

        if ($active) {
            $maxBot->sendToUser(
                $maxUserId,
                'Уведомления уже подключены.'
            );
        } else {
            $maxBot->requestPhone($maxUserId);
        }
    }

    private function subscribe(
        int|string $maxUserId,
        int|string|null $chatId,
        string $phone,
        MaxBotService $maxBot
    ): void {

        $result = DB::transaction(function () use (
            $maxUserId,
            $chatId,
            $phone
        ): string {

            // Нормализуем телефон, полученный из MAX
            $normalizedPhone = preg_replace('/\D+/', '', $phone);

            if ($normalizedPhone === '') {
                return 'invalid_phone';
            }

            // В базе код страны и номер хранятся отдельно
            $users = User::query()
                ->whereRaw(
                    'CONCAT(phone_prefix, phone) = ?',
                    [$normalizedPhone]
                )
                ->whereIn('role', [1, 3])
                ->limit(2)
                ->lockForUpdate()
                ->get();

            if ($users->isEmpty()) {
                Log::warning('MAX: пользователь не найден', [
                    'max_user_id' => $maxUserId,
                    'phone_last4' => substr($normalizedPhone, -4),
                ]);

                return 'not_found';
            }

            if ($users->count() > 1) {
                return 'ambiguous';
            }

            $user = $users->first();

            // Проверяем существующую привязку MAX ID
            $subscriptionByMaxId = MaxSubscription::query()
                ->where('max_user_id', $maxUserId)
                ->lockForUpdate()
                ->first();

            if (
                $subscriptionByMaxId !== null &&
                (string) $subscriptionByMaxId->user_id !==
                (string) $user->getKey()
            ) {
                return 'conflict';
            }

            // Проверяем привязку пользователя сайта
            $subscription = MaxSubscription::query()
                ->where('user_id', $user->getKey())
                ->lockForUpdate()
                ->first();

            if (
                $subscription !== null &&
                (string) $subscription->max_user_id !==
                (string) $maxUserId
            ) {
                return 'conflict';
            }

            // Создаём новую подписку
            if ($subscription === null) {
                MaxSubscription::query()->create([
                    'user_id' => $user->getKey(),
                    'max_user_id' => $maxUserId,
                    'chat_id' => $chatId,
                    'subscribed_at' => now(),
                ]);

                return 'subscribed';
            }

            // Восстанавливаем существующую подписку
            $alreadyActive = $subscription->subscribed_at !== null;

            if ($chatId !== null) {
                $subscription->chat_id = $chatId;
            }

            $subscription->subscribed_at =
                $subscription->subscribed_at ?? now();

            $subscription->save();

            return $alreadyActive
                ? 'already_subscribed'
                : 'subscribed';
        });

        Log::info('MAX: результат регистрации', [
            'max_user_id' => $maxUserId,
            'result' => $result,
        ]);

        $message = match ($result) {
            'subscribed' =>
            'Телефон подтверждён. Подписка на уведомления активирована.',

            'already_subscribed' =>
            'Вы уже подписаны на уведомления.',

            'not_found' =>
            'Пользователь с таким номером не найден в базе данных.',

            'ambiguous' =>
            'Найдено несколько пользователей с таким номером. Обратитесь в поддержку.',

            'invalid_phone' =>
            'Не удалось распознать номер телефона.',

            default =>
            'Этот аккаунт уже привязан к другому пользователю. Обратитесь в поддержку.',
        };

        $maxBot->sendToUser($maxUserId, $message);
    }
}
