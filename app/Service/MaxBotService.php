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
        array $options = [],
        array $buttons = []
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
            Log::warning('MAX: контакт не обработан', [
                'reason' => 'attachments_not_array',
            ]);
            return null;
        }

        foreach ($attachments as $attachment) {
            if (!is_array($attachment) || ($attachment['type'] ?? '') !== 'contact') {
                continue;
            }

            $payload = $attachment['payload'] ?? null;
            if (!is_array($payload)) {
                Log::warning('MAX: контакт не обработан', [
                    'reason' => 'missing_contact_payload',
                ]);
                return null;
            }

            $rawVcf = $payload['vcf_info'] ?? null;
            $hash = $payload['hash'] ?? null;

            if (!is_string($rawVcf) || $rawVcf === '') {
                Log::warning('MAX: контакт не обработан', [
                    'reason' => 'missing_vcf_info',
                ]);
                return null;
            }

            if (!is_string($hash) || trim($hash) === '') {
                Log::warning('MAX: контакт не обработан', [
                    'reason' => 'missing_hash',
                    'note' => 'Контакт должен быть отправлен кнопкой request_contact',
                ]);
                return null;
            }

            $received = trim($hash);
            $isHexHash = preg_match('/^[a-f0-9]{64}$/iD', $received) === 1;
            $base64Hash = base64_decode(strtr($received, '-_', '+/'), true);

            // Сохраняем исходные байты и проверяем распространённые варианты
            // переноса строк. НИКОГДА не отключаем проверку HMAC.
            $restoredVcf = str_replace(
                ['\\r\\n', '\\n', '\\r'],
                ["\r\n", "\n", "\r"],
                $rawVcf
            );

            $candidates = [
                'raw' => $rawVcf,
                'escaped_restored' => $restoredVcf,
                'raw_lf' => str_replace(["\r\n", "\r"], "\n", $rawVcf),
                'restored_lf' => str_replace(["\r\n", "\r"], "\n", $restoredVcf),
            ];

            $verifiedVcf = null;
            $verifiedVariant = null;

            foreach ($candidates as $variant => $vcf) {
                $mac = hash_hmac('sha256', $vcf, $this->token, true);

                $validHex = $isHexHash && hash_equals(bin2hex($mac), strtolower($received));
                $validBase64 = $base64Hash !== false && hash_equals($mac, $base64Hash);

                if ($validHex || $validBase64) {
                    $verifiedVcf = $vcf;
                    $verifiedVariant = $variant;
                    break;
                }
            }

            if ($verifiedVcf === null) {
                Log::warning('MAX: контакт не обработан', [
                    'reason' => 'invalid_contact_hash',
                    'hash_length' => strlen($received),
                    'vcf_has_real_crlf' => str_contains($rawVcf, "\r\n"),
                    'vcf_has_escaped_crlf' => str_contains($rawVcf, '\\r\\n'),
                    'token_configured' => $this->token !== '',
                ]);
                return null;
            }

            // Для извлечения телефона нормализуем переносы ПОСЛЕ проверки подписи.
            $vcfForPhone = str_replace(
                ['\\r\\n', '\\n', '\\r'],
                ["\n", "\n", "\n"],
                $verifiedVcf
            );
            $vcfForPhone = str_replace(["\r\n", "\r"], "\n", $vcfForPhone);

            preg_match_all(
                '/^(?:[a-z0-9_-]+\\.)?TEL(?:;[^:\\r\\n]*)?:[ \\t]*([^\\r\\n]+)/mi',
                $vcfForPhone,
                $matches
            );

            if (count($matches[1]) !== 1) {
                Log::warning('MAX: контакт не обработан', [
                    'reason' => 'invalid_tel_count',
                    'tel_count' => count($matches[1]),
                    'verified_variant' => $verifiedVariant,
                ]);
                return null;
            }

            $phone = self::normalizeRussianPhone(trim($matches[1][0]));
            if ($phone === null) {
                Log::warning('MAX: контакт не обработан', [
                    'reason' => 'phone_normalization_failed',
                    'verified_variant' => $verifiedVariant,
                ]);
                return null;
            }

            return [
                'phone' => $phone,
                'max_user_id' => data_get($payload, 'max_info.user_id'),
            ];
        }

        Log::warning('MAX: контакт не обработан', [
            'reason' => 'no_contact_attachment',
        ]);
        return null;
    }

    /**
     * Повторная проверка подписанного контакта по каноническому сообщению MAX API.
     * Использовать только для webhook с contact, если HMAC исходного VCF не сошёлся.
     * Номер возвращается ТОЛЬКО после проверки HMAC методом extractPhoneFromMaxMessage().
     *
     * @return array{phone:string, max_user_id:int|string|null}|null
     */
    public function extractVerifiedContactViaApi(
        array $webhookMessage,
        int|string $expectedUserId,
        int|string|null $expectedChatId
    ): ?array {
        $mid = data_get($webhookMessage, 'body.mid');
        if (!is_string($mid) || $mid === '') {
            Log::warning('MAX: повторная проверка невозможна', ['reason' => 'missing_message_id']);
            return null;
        }

        // Нельзя принимать произвольный контакт без hash от request_contact.
        $webhookAttachments = data_get($webhookMessage, 'body.attachments', []);
        if (!is_array($webhookAttachments)) {
            return null;
        }
        $webhookHash = null;
        foreach ($webhookAttachments as $attachment) {
            if (is_array($attachment) && ($attachment['type'] ?? null) === 'contact') {
                $webhookHash = data_get($attachment, 'payload.hash');
                break;
            }
        }
        if (!is_string($webhookHash) || $webhookHash === '') {
            return null;
        }

        $request = Http::withHeaders(['Authorization' => $this->token])->timeout(10);
        // На Ubuntu используется то же доверенное CA-хранилище, что и у системного curl.
        // Опционально задаётся MAX_CA_BUNDLE (services.max.ca_bundle).
        $caBundle = (string) config('services.max.ca_bundle', '/etc/ssl/certs/ca-certificates.crt');
        if ($caBundle !== '') {
            $request = $request->withOptions(['verify' => $caBundle]);
        }

        $response = $request->get($this->apiUrl . '/messages', ['message_ids' => $mid]);
        // Ошибки API/сети нельзя трактовать как «неподтверждённый номер»:
        // выдаём ошибку, чтобы MAX мог повторно доставить webhook.
        $response->throw();

        $messages = $response->json('messages');
        if (!is_array($messages) || count($messages) !== 1 || !is_array($messages[0])) {
            Log::warning('MAX: повторная проверка невозможна', [
                'reason' => 'api_message_not_found',
                'message_id' => $mid,
            ]);
            return null;
        }

        $apiMessage = $messages[0];
        $apiMid = data_get($apiMessage, 'body.mid');
        $apiUserId = data_get($apiMessage, 'sender.user_id');
        $apiChatId = data_get($apiMessage, 'recipient.chat_id');
        $apiChatType = data_get($apiMessage, 'recipient.chat_type');

        // Защита от подмены/связывания контакта другого пользователя или чата.
        if (!is_string($apiMid) || !hash_equals($mid, $apiMid)
            || $apiUserId === null || (string) $apiUserId !== (string) $expectedUserId
            || ($expectedChatId !== null && (
                $apiChatId === null || (string) $apiChatId !== (string) $expectedChatId
            ))
            || ($apiChatType !== null && $apiChatType !== 'dialog')) {
            Log::warning('MAX: повторная проверка отклонена', [
                'reason' => 'message_context_mismatch',
                'message_id' => $mid,
            ]);
            return null;
        }

        $apiAttachments = data_get($apiMessage, 'body.attachments', []);
        $apiHash = null;
        if (is_array($apiAttachments)) {
            foreach ($apiAttachments as $attachment) {
                if (is_array($attachment) && ($attachment['type'] ?? null) === 'contact') {
                    $apiHash = data_get($attachment, 'payload.hash');
                    break;
                }
            }
        }

        if (!is_string($apiHash) || !hash_equals($webhookHash, $apiHash)) {
            Log::warning('MAX: повторная проверка отклонена', [
                'reason' => 'contact_hash_mismatch',
                'message_id' => $mid,
            ]);
            return null;
        }

        // Здесь проверяется настоящая подпись VCF, полученного непосредственно из MAX API.
        $contact = $this->extractPhoneFromMaxMessage($apiMessage);
        if ($contact !== null) {
            Log::info('MAX: контакт успешно проверен через API', ['message_id' => $mid]);
        }

        return $contact;
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
