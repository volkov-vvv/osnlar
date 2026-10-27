<?php

namespace App\Http\Controllers\lid;

use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Lid;
use App\Models\Link;
use App\Models\Order;
use App\Models\Status;
use App\Models\TelegraphMessage;
use App\Models\User;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Lid\StoreNewRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Service\MaxBotService;

class StoreNewController extends Controller
{
    public function __invoke(StoreNewRequest $request, MaxBotService $maxBot)
    {
        $data = $request->safe()->except('smart-token');
        $course_link = '';
        $mail_template = 'mails.lid_year';
        $course = Course::query()->findOrFail($request->course_id);
        if($course->open_registration == 1){
            $mail_template = 'mails.lid_link';
            // Определение, доступен ли курс в регионе.
            $links = Link::all()->where('region_id', $request->region_id)->where('course_id', $request->course_id)->last();
            if($links){
                $course_link = $links->link;
                if (!$course->default_status_id) {
                    abort(422, 'У курса не задан статус по умолчанию');
                }
                $data['status_id'] = $course->default_status_id;
            }else{
                    $status = Status::all()->where('code', 'not-in-region')->first();
                    $data['status_id'] =  $status->id;
            }
        }else{
            if (!$course->default_status_id) {
                abort(422, 'У курса не задан статус по умолчанию');
            }
            $data['status_id'] = $course->default_status_id;
        }

        $lid = Lid::firstOrCreate($data);
        $data['id'] = $lid->id;
        $data['link'] = $course_link;
        $data['course_title'] = $course->title;
        $data['region'] = $lid->region->title;

        $mailData = collect($data);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = $mail_template;
        \Mail::to($data['email'])->send(new SendEmail($mailData));

        $buttons = [
            'inline_keyboard' =>[
                [
                    [
                        'text' => 'Принять',
                        'callback_data' => '1',
                    ],
                    [
                        'text' => 'Отклонить',
                        'callback_data' => '0',
                    ],
                ]
            ]
        ];

/*
 * Отключаем отправку сообщений через Телеграм
 *
 *
        $users = User::whereIn('role', [1,3])->whereNotNull('telegraph_chat_id')->get();

        foreach ($users as $user) {

            $chat = TelegraphChat::find($user->telegraph_chat_id);

            if($user->role == 3){

                $response = $chat->html((string)view('messages.new_lid', $data))->keyboard(
                    Keyboard::make()->button('Принять заявку')->action('lid_responsible')->param('lid_id', $data['id'])
                )->send();
            }else{

                $response = $chat->html((string)view('messages.new_lid', $data))->send();
            }
            $messageId = $response->telegraphMessageId();
            if($messageId){
                $messageData = [
                    'chat_id' => $chat->chat_id,
                    'message_id' => $messageId,
                    'user_id' => $user->id,
                    'lid_id' => $lid->id,
                    'text' => (string) view('messages.new_lid', $data),
                ];
                TelegraphMessage::firstOrCreate($messageData);
            }
        }
        */

        // Отправка сообщения в MAX

        $users = User::query()
            ->whereHas('maxSubscription', function ($query) {
                $query->whereNotNull('subscribed_at');
            })
            ->get();

        dd($users);

        $message = view('messages.new_lid',$data)->render();

        $buttons = [
            [
                MaxBotService::callbackButton(
                    'Принять заявку',
                    'accept_lid:' . $lid->id
                ),
            ],
        ];

        foreach ($users as $user) {
            $maxBot->sendNotificationToUser(
                localUserId: $user->id,
                text: trim($message),
                lidId: $lid->id,
                options: ['format' => 'html'],
                buttons: $buttons
            );
        }
        return redirect()->route('lid.index');

    }
}
