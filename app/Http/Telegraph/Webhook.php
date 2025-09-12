<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;

class Webhook extends WebhookHandler
{
    public function start(string $userName){
//        $username = $this->message->from()->firstName();
        $this->message($userName)->dd();
//        $this->chat->html('Привет,' . $userName . '!')->send();
    }
}
