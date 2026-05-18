<?php

namespace App\Services;

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

        Log::debug($response);

        if (!$response->successful()) {
            Log::error('MAX API send message error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->json() ?? [];
    }
}
