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

        $this->chat->html("Проверка: $phone, $userId, $verifyUserId, результат: $isVerifyPhone")->send();
        $phone = str_replace('+', '', $phone);
        $user = \App\Models\User::where(\DB::raw("concat(phone_prefix,phone)"),"like", $phone)->whereIn('role', [1,3])->get();
        Log::debug($user);
    }
}
