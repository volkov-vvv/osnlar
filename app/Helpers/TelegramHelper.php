<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Http;

class Telegram
{
    protected $http;
    protected $bot;
    const url = 'https://api.telegram.org/bot';

    public function __construct(Http $http, $bot)
    {
        $this->http = $http;
        $this->bot = $bot;
    }

    public function sendMessage($chat_id, $message){
        $this->http::post(self::url.$this->bot.'/sendMessage', [
            'chat_id' => $chat_id, //'708532278'
            'text' => $message,
            'parse_mode' => 'html'
        ]);
    }
}
