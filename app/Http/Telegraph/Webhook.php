<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Facades\Log;

class Webhook extends WebhookHandler
{
    public function start(){

//        Log::debug($this->message->from());
//        $this->chat->html($userName)->dd();
//        $this->chat->html('Привет, ' . $this->message->from()->firstName() . '!')->send();
        $this->chat->message('Здравствуйте! Для авторизации нужно отправить свой номер телефона.')->replyKeyboard(ReplyKeyboard::make()->oneTime()->buttons([
            ReplyButton::make('Отправить номер телефона')->requestContact()
        ]))->send();
    }
}
