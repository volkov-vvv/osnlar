<?php

namespace App\Http\Controllers\lid;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Lid;
use App\Models\Link;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Lid\StoreNewRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StoreNewController extends Controller
{
    public function __invoke(StoreNewRequest $request)
    {
        $data = $request->validated();
        $mail_template = 'mails.lid_year';
        $course = Course::all()->where('id', $request->course_id)->first();
        if($course->open_registration == 1){
            $mail_template = 'mails.lid_link';
            // Определение, доступен ли курс в регионе.
            $links = Link::all()->where('region_id', $request->region_id)->where('course_id', $request->course_id)->last();
            if($links){
                $link = $links->link;
            }else{
                $link = '';

                    $status = Status::all()->where('code', 'not-in-region')->first();
                    $data['status_id'] =  $status->id;

            }
        }else{
            // Всем остальным заявкам присваиваем статус 'На 2025 год'
            $status = Status::all()->where('title', 'На 2025 год')->first();
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
