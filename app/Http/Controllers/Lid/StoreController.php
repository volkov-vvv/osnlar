<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lid\StoreRequest;
use App\Models\Lid;
use App\Models\Link;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Mail\SendEmail;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $links = Link::all()->where('region_id', $request->region_id)->where('course_id', $request->course_id)->last();
        if($links){
            $link = $links->link;
        }else{
            $link = '';
            $status = Status::all()->where('code', 'not-in-region')->first();
            $data['status_id'] =  $status->id;
        }
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
