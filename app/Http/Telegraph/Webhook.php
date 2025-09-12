<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\Handlers\WebhookHandler;

class Webhook extends WebhookHandler
{
    public function start(){
        $this->chat->html('Привет!')->send();
    }
}
