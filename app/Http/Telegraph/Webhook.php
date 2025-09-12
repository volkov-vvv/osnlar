<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\Handlers\WebhookHandler;

class Webhook extends WebhookHandler
{
    public function start(string $userName){
//        $username = $this->message->from()->firstName();
        $this->chat->html('Привет,' . $userName . '!')->send();
    }
}
