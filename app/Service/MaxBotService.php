<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MaxBotService
{
    private string $token;
    private string $apiUrl;

    public function __construct()
    {
        $this->token = config('services.max.token');
        $this->apiUrl = rtrim(config('services.max.api_url'), '/');
    }

    public function sendMessageToUser(int|string $userId, string $text): array
    {
        return $this->postMessage(['user_id' => $userId], $text);
    }

    public function sendMessageToChat(int|string $chatId, string $text): array
    {
        return $this->postMessage(['chat_id' => $chatId], $text);
    }

    private function postMessage(array $query, string $text): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/messages?' . http_build_query($query), [
            'text' => $text,
            'notify' => true,
        ]);

//        Log::debug($response);

        if (!$response->successful()) {
            Log::error('MAX API send message error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->json() ?? [];
    }

    public function sendMessage(
        string $targetType,
        int|string $targetId,
        string $text,
        array $buttons = [],
        array $options = []
    ): array {
        if (!in_array($targetType, ['user_id', 'chat_id'], true)) {
            throw new InvalidArgumentException('targetType должен быть user_id или chat_id');
        }

        $query = [
            $targetType => $targetId,
        ];

        if (array_key_exists('disable_link_preview', $options)) {
            $query['disable_link_preview'] = (bool) $options['disable_link_preview'];
        }

        $body = [
            'text' => $text,
            'notify' => $options['notify'] ?? true,
        ];

        if (!empty($options['format'])) {
            $body['format'] = $options['format']; // markdown или html
        }

        if (!empty($buttons)) {
            $body['attachments'] = [
                [
                    'type' => 'inline_keyboard',
                    'payload' => [
                        'buttons' => $buttons,
                    ],
                ],
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/messages?' . http_build_query($query), $body);

        if (!$response->successful()) {
            Log::error('MAX API sendMessage error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'request' => [
                    'query' => $query,
                    'body' => $body,
                ],
            ]);
        }

        return $response->json() ?? [
                'ok' => false,
                'status' => $response->status(),
                'body' => $response->body(),
            ];
    }

    public function sendToChat(int|string $chatId, string $text, array $buttons = [], array $options = []): array
    {
        return $this->sendMessage('chat_id', $chatId, $text, $buttons, $options);
    }

    public function sendToUser(int|string $userId, string $text, array $buttons = [], array $options = []): array
    {
        return $this->sendMessage('user_id', $userId, $text, $buttons, $options);
    }

    public static function callbackButton(string $text, string $payload): array
    {
        return [
            'type' => 'callback',
            'text' => $text,
            'payload' => $payload,
        ];
    }

    public static function linkButton(string $text, string $url): array
    {
        return [
            'type' => 'link',
            'text' => $text,
            'url' => $url,
        ];
    }

    public static function messageButton(string $text, string $message): array
    {
        return [
            'type' => 'message',
            'text' => $text,
            'payload' => $message,
        ];
    }

    public static function requestContactButton(string $text = 'Поделиться телефоном'): array
    {
        return [
            'type' => 'request_contact',
            'text' => $text,
        ];
    }

    public static function requestGeoButton(string $text = 'Поделиться геопозицией'): array
    {
        return [
            'type' => 'request_geo_location',
            'text' => $text,
        ];
    }

    public static function clipboardButton(string $text, string $payload): array
    {
        return [
            'type' => 'clipboard',
            'text' => $text,
            'payload' => $payload,
        ];
    }

    private function extractPhoneFromMaxMessage(array $message): ?array
    {
        $attachments = $message['body']['attachments'] ?? [];

        foreach ($attachments as $attachment) {
            if (($attachment['type'] ?? '') !== 'contact') {
                continue;
            }

            $payload = $attachment['payload'] ?? [];
            $vcfInfo = $payload['vcf_info'] ?? '';
            $hash = $payload['hash'] ?? null;

            if ($vcfInfo === '') {
                continue;
            }

            if (preg_match('/TEL[^:]*:\s*([+\d][\d\s().-]*)/iu', $vcfInfo, $matches)) {
                $rawPhone = trim($matches[1]);
                $phone = preg_replace('/[^\d+]/', '', $rawPhone);

                return [
                    'phone' => $phone,
                    'vcf_info' => $vcfInfo,
                    'hash' => $hash,
                ];
            }
        }

        return null;
    }


}
