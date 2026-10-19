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
                    $mid = data_get($message, 'body.mid');

                    if ($mid) {
                        $apiResponse = \Illuminate\Support\Facades\Http::withOptions([
                            'verify' => '/etc/ssl/certs/ca-certificates.crt',
                        ])->withHeaders([
                            'Authorization' => config('services.max.token'),
                        ])->get('https://platform-api2.max.ru/messages', [
                            'message_ids' => $mid,
                        ]);

                        $webhookAttachment = collect(
                            data_get($message, 'body.attachments', [])
                        )->firstWhere('type', 'contact');

                        $apiAttachment = collect(
                            $apiResponse->json('messages.0.body.attachments', [])
                        )->firstWhere('type', 'contact');

                        $webhookVcf = data_get($webhookAttachment, 'payload.vcf_info');
                        $apiVcf = data_get($apiAttachment, 'payload.vcf_info');

                        $webhookHash = data_get($webhookAttachment, 'payload.hash');
                        $apiHash = data_get($apiAttachment, 'payload.hash');

                        $checkHash = static function ($vcf, $hash): ?bool {
                            if (!is_string($vcf) || !is_string($hash)) {
                                return null;
                            }

                            return hash_equals(
                                hash_hmac(
                                    'sha256',
                                    $vcf,
                                    config('services.max.token')
                                ),
                                strtolower($hash)
                            );
                        };

                        Log::warning('MAX: диагностика подписи контакта', [
                            'message_id' => $mid,
                            'api_status' => $apiResponse->status(),

                            'vcf_same' => is_string($webhookVcf)
                                && is_string($apiVcf)
                                && $webhookVcf === $apiVcf,

                            'hash_same' => is_string($webhookHash)
                                && is_string($apiHash)
                                && hash_equals($webhookHash, $apiHash),

                            'webhook_hmac_valid' => $checkHash(
                                $webhookVcf,
                                $webhookHash
                            ),

                            'api_hmac_valid' => $checkHash(
                                $apiVcf,
                                $apiHash
                            ),
                        ]);
                    }
                }

// Получаем номер непосредственно из контакта MAX
                $vcf = $attachment['payload']['vcf_info'] ?? '';
                $vcf = str_replace(['\\r\\n', '\\n'], ["\r\n", "\n"], $vcf);

                $phoneFromMax = null;

                if (preg_match('/^TEL(?:;[^:\r\n]*)?:[ \t]*([^\r\n]+)/mi', $vcf, $matches)) {
                    $phoneFromMax = trim($matches[1]);
                }

                if ($contact === null ||
                    ($contact['max_user_id'] !== null &&
                        (string) $contact['max_user_id'] !== (string) $maxUserId)) {

                    Log::warning('MAX: Контакт пользователя не подтверждён', [
                        'reason' => $contact === null
                            ? 'Контакт отсутствует или не прошёл проверку'
                            : 'MAX ID контакта не совпадает с ID отправителя',

                        'max_user_id' => $maxUserId,
                        'contact_max_user_id' => $contact['max_user_id'] ?? null,
                        'contact_is_null' => $contact === null,
                        'phone_from_max' => $phoneFromMax,
                        'normalized_phone' => $contact['phone'] ?? null,
                    ]);

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

    private function requestPhoneIfNeeded(int|string $maxUserId, MaxBotService $maxBot): void
    {
        $active = MaxSubscription::query()
            ->where('max_user_id', $maxUserId)
            ->active()
            ->exists();

        if ($active) {
            $maxBot->sendToUser($maxUserId, 'Уведомления уже подключены.');
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
        $result = DB::transaction(function () use ($maxUserId, $chatId, $phone): string {
            // users.phone должен храниться в таком же формате: 79991234567.
            $phonePrefix = 7;
            $phoneNumber = substr($phone, 1);

            $users = User::query()
                ->where('phone_prefix', $phonePrefix)
                ->where('phone', $phoneNumber)
                ->whereIn('role', [1, 3])
                ->limit(2)
                ->lockForUpdate()
                ->get();

            if ($users->isEmpty()) {
                Log::warning('MAX: Пользователь не найден в базе', [
                    'max_user_id' => $maxUserId,
                    'phone_prefix' => $phonePrefix,
                    'phone_last4' => substr($phoneNumber, -4),
                    'allowed_roles' => [1, 3],
                ]);
                return 'not_found';
            }
            if ($users->count() > 1) {
                return 'ambiguous';
            }

            $user = $users->first();
            $subscriptionByMaxId = MaxSubscription::query()
                ->where('max_user_id', $maxUserId)
                ->lockForUpdate()
                ->first();

            if ($subscriptionByMaxId !== null &&
                (string) $subscriptionByMaxId->user_id !== (string) $user->getKey()) {
                return 'conflict';
            }

            $subscription = MaxSubscription::query()
                ->where('user_id', $user->getKey())
                ->lockForUpdate()
                ->first();

            if ($subscription !== null &&
                (string) $subscription->max_user_id !== (string) $maxUserId) {
                return 'conflict';
            }

            if ($subscription === null) {
                MaxSubscription::query()->create([
                    'user_id' => $user->getKey(),
                    'max_user_id' => $maxUserId,
                    'chat_id' => $chatId,
                    'subscribed_at' => now(),
                ]);
                return 'subscribed';
            }

            $alreadyActive = $subscription->subscribed_at !== null;
            if ($chatId !== null) {
                $subscription->chat_id = $chatId;
            }
            $subscription->subscribed_at = $subscription->subscribed_at ?? now();
            $subscription->save();
            return $alreadyActive ? 'already_subscribed' : 'subscribed';
        });

        $message = match ($result) {
            'subscribed' => 'Телефон подтверждён. Подписка на уведомления активирована.',
            'already_subscribed' => 'Вы уже подписаны на уведомления.',
            'not_found' => 'Пользователь с таким номером не найден в базе данных.',
            'ambiguous' => 'Найдено несколько пользователей с таким номером. Обратитесь в поддержку.',
            default => 'Этот аккаунт уже привязан к другому MAX ID или пользователю. Обратитесь в поддержку.',
        };

        $maxBot->sendToUser($maxUserId, $message);
    }
}
