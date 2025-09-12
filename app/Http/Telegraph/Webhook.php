<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;


class Webhook extends WebhookHandler
{
    public function start(){

//        Log::debug($this->message->from());

        $this->chat->message('Здравствуйте! Для авторизации нужно отправить свой номер телефона.')->replyKeyboard(ReplyKeyboard::make()->oneTime()->buttons([
            ReplyButton::make('Отправить номер телефона')->requestContact()
        ]))->send();
    }


    protected function handleChatMessage(Stringable $text): void
    {
        $phone = $this->message->contact()->phoneNumber();
        $userId = $this->message->contact()->userId();
        $verifyUserId = $this->message->from()->id();
        $isVerifyPhone = intval($userId == $verifyUserId);
        Log::debug($this->message->contact());
        $this->chat->html("Проверка: $phone, $userId, $verifyUserId, результат: $isVerifyPhone")->send();
 //       $user = \App\Models\User::where('phone', );
    }
}
