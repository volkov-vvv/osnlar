<?php

namespace App\Http\Controllers\lid;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Lid;
use App\Models\Link;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Lid\StoreNewRequest;

class StoreNewController extends Controller
{
    public function __invoke(StoreNewRequest $request)
    {
        $data = $request->validated();
        /* Определение, доступен ли курс в регионе. Пока проверка не нужна
        $links = Link::all()->where('region_id', $request->region_id)->where('course_id', $request->course_id)->last();
        if($links){
            $link = $links->link;
        }else{
            $link = '';
            $status = Status::all()->where('code', 'not-in-region')->first();
            $data['status_id'] =  $status->id;
        }
        */

        // Всем заявкам присваиваем статус 'На 2025 год'
        $status = Status::all()->where('title', 'На 2025 год')->first();
        $data['status_id'] =  $status->id;

        $lid = Lid::firstOrCreate($data);
        $data['link'] = $link;
        $data['id'] = $lid->id;
        $mailData = collect($data);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = 'mails.template';
        \Mail::to($data['email'])->send(new SendEmail($mailData));

        return redirect()->route('lid.index');

    }
}
