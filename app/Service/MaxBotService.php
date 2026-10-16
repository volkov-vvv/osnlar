<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\MaxSentMessage;
use App\Models\MaxSubscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class MaxBotService
{
    private string $token;
    private string $apiUrl;

    public function __construct()
    {
        $this->token = (string) config('services.max.token', '');
        $this->apiUrl = rtrim(
            (string) (config('services.max.api_url') ?: 'https://platform-api2.max.ru'),
            '/'
        );

        if ($this->token === '') {
            throw new RuntimeException('Не задан MAX_BOT_TOKEN (services.max.token).');
        }
    }

    public function sendMessageToUser(int|string $userId, string $text): array
    {
        return $this->sendToUser($userId, $text);
    }

    public function sendMessageToChat(int|string $chatId, string $text): array
    {
        return $this->sendToChat($chatId, $text);
    }

    /**
     * Отправка через MAX API. Все успешные ответы записываются в max_sent_messages.
     * $options может содержать lid_id, local_user_id, notify, format, disable_link_preview.
     */
    public function sendMessage(
        string $targetType,
        int|string $targetId,
        string $text,
        array $buttons = [],
        array $options = []
    ): array {
        if (!in_array($targetType, ['user_id', 'chat_id'], true)) {
            throw new InvalidArgumentException('targetType должен быть user_id или chat_id.');
        }

        $query = [$targetType => $targetId];
        if (array_key_exists('disable_link_preview', $options)) {
            $query['disable_link_preview'] = $options['disable_link_preview'] ? 'true' : 'false';
        }

        $body = [
            'text' => $text,
            'notify' => (bool) ($options['notify'] ?? true),
        ];

        if (isset($options['format'])) {
            if (!in_array($options['format'], ['html', 'markdown'], true)) {
                throw new InvalidArgumentException('format должен быть html или markdown.');
            }
            $body['format'] = $options['format'];
        }

        if ($buttons !== []) {
            $body['attachments'] = [[
                'type' => 'inline_keyboard',
                'payload' => ['buttons' => $buttons],
            ]];
        }

        $response = Http::withHeaders(['Authorization' => $this->token])
            ->asJson()
            ->timeout(10)
            ->post($this->apiUrl . '/messages?' . http_build_query($query), $body);

        if ($response->failed()) {
            Log::error('MAX API: сообщение не отправлено', [
                'status' => $response->status(),
                'target_type' => $targetType,
                'target_id' => (string) $targetId,
            ]);
            $response->throw();
        }

        $result = $response->json();
        if (!is_array($result)) {
            throw new RuntimeException('MAX вернул неожиданный формат ответа на отправку сообщения.');
        }

        // Документация POST /messages: ответ содержит объект Message.
        // Поддерживаем обе формы сериализации: {message:{...}} и {...}.
        $sent = $result['message'] ?? $result;
        if (!is_array($sent)) {
            throw new RuntimeException('MAX не вернул объект Message.');
        }

        $mid = data_get($sent, 'body.mid');
        if (!is_string($mid) || $mid === '') {
            throw new RuntimeException('MAX API вернул сообщение без строкового body.mid: журнал не сохранён.');
        }
        $chatId = data_get($sent, 'recipient.chat_id');
        $maxUserId = $targetType === 'user_id'
            ? $targetId
            : data_get($sent, 'recipient.user_id');

        if ($chatId === null && $targetType === 'chat_id') {
            $chatId = $targetId;
        }

        $localUserId = $options['local_user_id'] ?? null;
        if ($localUserId === null) {
            $subscriptionQuery = MaxSubscription::query();
            if ($targetType === 'user_id') {
                $subscriptionQuery->where('max_user_id', $targetId);
            } else {
                $subscriptionQuery->where('chat_id', $targetId);
            }
            $localUserId = $subscriptionQuery->value('user_id');
        }

        MaxSentMessage::query()->create([
            'chat_id' => $chatId,
            'message_id' => $mid,
            'user_id' => $localUserId,
            'lid_id' => $options['lid_id'] ?? null,
            'text' => $text,
            'max_user_id' => $maxUserId,
        ]);

        return $result;
    }

    public function sendToChat(
        int|string $chatId,
        string $text,
        array $buttons = [],
        array $options = []
    ): array {
        return $this->sendMessage('chat_id', $chatId, $text, $buttons, $options);
    }

    public function sendToUser(
        int|string $userId,
        string $text,
        array $buttons = [],
        array $options = []
    ): array {
        return $this->sendMessage('user_id', $userId, $text, $buttons, $options);
    }

    /**
     * Для уведомления локальному пользователю, который активно подписан.
     * lid_id — необязательный идентификатор из вашей предметной модели.
     */
    public function sendNotificationToUser(
        int $localUserId,
        string $text,
        ?int $lidId = null,
        array $options = []
    ): array
    {
        $subscription = MaxSubscription::query()
            ->where('user_id', $localUserId)
            ->active()
            ->first();

        if ($subscription === null) {
            throw new RuntimeException('У пользователя нет активной подписки на MAX.');
        }

        return $this->sendToUser($subscription->max_user_id, $text, [], array_merge($options, [
            'local_user_id' => $localUserId,
            'lid_id' => $lidId,
        ]));
    }

    public function requestPhone(
        int|string $maxUserId,
        string $text = 'Для подключения уведомлений нажмите кнопку и поделитесь телефоном, привязанным к MAX.'
    ): array {
        return $this->sendToUser(
            $maxUserId,
            $text,
            [[self::requestContactButton('Поделиться телефоном')]]
        );
    }

    public static function callbackButton(string $text, string $payload): array
    {
        return ['type' => 'callback', 'text' => $text, 'payload' => $payload];
    }

    public static function linkButton(string $text, string $url): array
    {
        return ['type' => 'link', 'text' => $text, 'url' => $url];
    }

    public static function messageButton(string $text, string $message): array
    {
        return ['type' => 'message', 'text' => $text, 'payload' => $message];
    }

    public static function requestContactButton(string $text = 'Поделиться телефоном'): array
    {
        return ['type' => 'request_contact', 'text' => $text];
    }

    public static function requestGeoButton(string $text = 'Поделиться геопозицией'): array
    {
        return ['type' => 'request_geo_location', 'text' => $text];
    }

    public static function clipboardButton(string $text, string $payload): array
    {
        return ['type' => 'clipboard', 'text' => $text, 'payload' => $payload];
    }

    /**
     * Только подписанный контакт кнопки request_contact может подтверждать телефон.
     * @return array{phone:string, max_user_id:int|string|null}|null
     */
    public function extractPhoneFromMaxMessage(array $message): ?array
    {
        $attachments = data_get($message, 'body.attachments', []);
        if (!is_array($attachments)) {
            return null;
        }

        foreach ($attachments as $attachment) {
            if (!is_array($attachment) || ($attachment['type'] ?? '') !== 'contact') {
                continue;
            }

            $payload = $attachment['payload'] ?? null;
            if (!is_array($payload)) {
                return null;
            }

            $rawVcf = $payload['vcf_info'] ?? null;
            $hash = $payload['hash'] ?? null;
            if (!is_string($rawVcf) || $rawVcf === '' || !is_string($hash) || $hash === '') {
                return null;
            }

            // MAX требует заменить буквальные \r\n на настоящие переносы перед HMAC.
            $vcf = str_replace(['\\r\\n', '\\n'], ["\r\n", "\n"], $rawVcf);
            $hmacBytes = hash_hmac('sha256', $vcf, $this->token, true);
            $received = trim($hash);

            // Допустим обе стандартные сериализации SHA-256 — hex и base64.
            $validHex = preg_match('/^[a-fA-F0-9]{64}$/D', $received) === 1
                && hash_equals(bin2hex($hmacBytes), strtolower($received));

            $decoded = base64_decode(strtr($received, '-_', '+/'), true);
            $validBase64 = $decoded !== false && hash_equals($hmacBytes, $decoded);

            if (!$validHex && !$validBase64) {
                return null;
            }

            preg_match_all('/^TEL(?:;[^:\r\n]*)?:[ \t]*([^\r\n]+)/mi', $vcf, $matches);
            if (count($matches[1]) !== 1) {
                return null;
            }

            $phone = self::normalizeRussianPhone($matches[1][0]);
            if ($phone === null) {
                return null;
            }

            return [
                'phone' => $phone,
                'max_user_id' => data_get($payload, 'max_info.user_id'),
            ];
        }

        return null;
    }

    public static function normalizeRussianPhone(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (strlen($digits) === 10) {
            $digits = '7' . $digits;
        } elseif (strlen($digits) === 11 && $digits[0] === '8') {
            $digits = '7' . substr($digits, 1);
        }

        return preg_match('/^7\d{10}$/D', $digits) ? $digits : null;
    }
}
