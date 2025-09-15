<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function index(Request $request, Telegram $telegram){
        Log::debug($request->all());
        $chat_id = $request->input('message')['chat']['id'];
        $from = $request->input('message')['chat']['first_name'] . ' ' . $request->input('message')['chat']['last_name'];
        $telegram->sendMessage($chat_id, 'Привет, ' . $from);
    }
}
