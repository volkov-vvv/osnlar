<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\Handlers\WebhookHandler;

class Webhook extends WebhookHandler
{
    public function start(){
        $username = $this->message->from_user->username;
        $this->chat->html('Привет,' . $username . '!')->send();
    }
}
