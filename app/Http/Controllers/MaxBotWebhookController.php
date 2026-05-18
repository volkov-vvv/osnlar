<?php

namespace App\Http\Controllers;

use App\Service\MaxBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MaxBotWebhookController extends Controller
{
    public function __invoke(Request $request, MaxBotService $maxBot): JsonResponse
    {

        Log::info('MAX webhook пришёл', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
        ]);

        $update = $request->all();

        Log::info('MAX webhook update', $update);



        $updateType = $update['update_type'] ?? null;

        if ($updateType === 'bot_started') {
            $chatId = $update['chat_id'] ?? null;

            if ($chatId) {
                $maxBot->sendMessageToChat($chatId, 'Здравствуйте! Бот запущен.');
            }

            return response()->json(['ok' => true]);
        }

        if ($updateType === 'message_created') {
            $message = $update['message'] ?? [];
            $text = trim((string)($message['body']['text'] ?? ''));

            $chatId = $message['recipient']['chat_id']
                ?? $update['chat_id']
                ?? null;

            $userId = $message['sender']['user_id']
                ?? $update['user']['user_id']
                ?? null;

            if ($text === '/start' || mb_strtolower($text) === 'старт') {
                $answer = 'Привет! Я бот MAX на Laravel.';
            } elseif ($text !== '') {
                $answer = 'Вы написали: ' . $text;
            } else {
                $answer = 'Я получил сообщение.';
            }

            if ($chatId) {
                $maxBot->sendMessageToChat($chatId, $answer);
            } elseif ($userId) {
                $maxBot->sendMessageToUser($userId, $answer);
            }
        }

        return response()->json(['ok' => true]);
    }
}
