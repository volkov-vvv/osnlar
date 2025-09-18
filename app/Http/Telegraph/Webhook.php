<?php


namespace App\Http\Telegraph;


use App\Models\Lid;
use DefStudio\Telegraph\DTO\Message;
use DefStudio\Telegraph\DTO\User;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Telegraph;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Stringable;


class Webhook extends WebhookHandler
{
    public function start(){

        Log::debug($this->message->from());

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

        if($isVerifyPhone == 1){
            $phone = str_replace('+', '', $phone);
            $user = \App\Models\User::where(\DB::raw("concat(phone_prefix,phone)"),"like", $phone)->whereIn('role', [1,3])->first();

            if($user){
                $chat = TelegraphChat::where('chat_id', $userId)->first();
                $user->telegraph_chat_id = $chat->id;
                $user->save();
                $this->chat->html("Здравствуйте,  $user->name. Вы успешно подписаны на рассылку по новым заявкам.")->send();
            }else{
                $this->chat->html("Вы не являетесь сотрудником компании.")->send();
            }
        }else{
            $this->chat->html("Вы отправили чужой номер телефона.")->send();
        }
    }

    public function lid_responsible(): void
    {
        $lidId = $this->data->get('lid_id');
        Log::debug($lidId);
        $lid = Lid::find($lidId);
        Log::debug($lid);
        if($lid->responsible_id){
            $this->chat->html('У этой заявки уже есть ответственный')->send();
        }else{
            $chat = TelegraphChat::where('chat_id', $this->chat->chat_id)->first();
            $user = \App\Models\User::where('telegraph_chat_id', $chat->id)->first();
            $lid->responsible_id = $user->id;
            $lid->save();
            $this->chat->html('Вы назначены ответсвенным за эту заявку')->send();
        }
    }
}
