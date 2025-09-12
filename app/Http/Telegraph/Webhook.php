<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;

class Webhook extends WebhookHandler
{
    public function start(User $user){
//        $username = $this->message->from()->firstName();
        $this->chat->html('Привет, ' . $user->firstName() . '!')->send();
    }
}
