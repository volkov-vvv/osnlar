<?php


namespace App\Http\Telegraph;


use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Facades\Log;

class Webhook extends WebhookHandler
{
    public function start(){
//        $username = $this->message->from()->firstName();
//        Telegraph::message('test')->dump();

        Log::debug($this->message->all());
//        $this->chat->html($userName)->dd();
        $this->chat->html('Привет, !')->send();
    }
}
