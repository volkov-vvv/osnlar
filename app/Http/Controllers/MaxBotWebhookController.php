<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MaxSubscription;
use App\Models\User;
use App\Service\MaxBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\Lid;
use App\Jobs\SyncMaxLidMessages;


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

        $text = trim((string) data_get($message, 'body.text', ''));
        if ($text !== '') {
            $this->requestPhoneIfNeeded($maxUserId, $maxBot);
        }
    }

    private function onMessageCallback(
        array $update,
        MaxBotService $maxBot
    ): void {

        Log::info('MAX: нажата кнопка', [
            'payload' => data_get($update, 'callback.payload'),
            'max_user_id' => data_get($update, 'callback.user.user_id'),
            'message_id' => data_get($update, 'message.body.mid'),
        ]);


        $payload = data_get($update, 'callback.payload');
        $callbackId = data_get($update, 'callback.callback_id');
        $maxUserId = data_get($update, 'callback.user.user_id');
        $messageId = data_get($update, 'message.body.mid');

        if (!is_string($payload) ||
            !preg_match('/^accept_lid:([1-9][0-9]*)$/D', $payload, $matches)) {
            return;
        }

        if (!$callbackId || !$maxUserId || !$messageId) {
            Log::warning('MAX: некорректный callback заявки');
            return;
        }

        $lidId = (int) $matches[1];

        $subscription = MaxSubscription::query()
            ->where('max_user_id', $maxUserId)
            ->active()
            ->first();

        $allowed = $subscription !== null &&
            \App\Models\MaxSentMessage::query()
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

// Получаем пользователя сайта
        $user = User::find($subscription->user_id);

// Проверяем роль пользователя
        if (!$user || !in_array((int) $user->role, [1, 3], true)) {
            $maxBot->answerCallback(
                $callbackId,
                'Недостаточно прав для принятия заявки.'
            );
            return;
        }

// Назначаем ответственного
        $result = DB::transaction(function () use ($lidId, $user) {

            $lid = Lid::query()
                ->whereKey($lidId)
                ->lockForUpdate()
                ->first();

            if (!$lid) {
                return 'not_found';
            }

            if ($lid->responsible_id !== null) {
                return 'already_assigned';
            }

            $lid->responsible_id = $user->id;
            $lid->save();

            return 'accepted';
        });

// Записываем результат в лог
        $notification = match ($result) {
            'accepted' => 'Вы назначены ответственным за эту заявку.',
            'already_assigned' => 'У этой заявки уже есть ответственный.',
            default => 'Заявка не найдена.',
        };

// Сначала ставим в очередь обновление сообщений.
// Ошибка callback не должна препятствовать этому.
        if ($result === 'accepted') {
            SyncMaxLidMessages::dispatch(
                $lidId,
                (int) $user->id
            );

            Log::info('MAX: обновление сообщений поставлено в очередь', [
                'lid_id' => $lidId,
                'responsible_id' => $user->id,
            ]);
        }

        try {
            // Подтверждаем нажатие кнопки
            $maxBot->answerCallback(
                $callbackId,
                e($notification)
            );

            // Отдельное сообщение пользователю в чат MAX
            $maxBot->sendToUser(
                $maxUserId,
                $notification
            );

        } catch (\Throwable $e) {
            Log::error('MAX: ошибка ответа на принятие заявки', [
                'lid_id' => $lidId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
