<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\Org\BioeconomyStoreRequest;
use App\Mail\SendEmail;
use App\Models\Org;
use Illuminate\Support\Facades\Mail;

class StoreBioeconomyController extends Controller
{
    public function __invoke(BioeconomyStoreRequest $request)
    {
        $data = $request->validated();
        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        $data['source'] = 'bioeconomy';
        $data['politic'] = 1;
        $data['status_id'] = 1;
        $data['course_id'] = $courseIds[0] ?? null;

        $org = Org::create($data);
        $org->courses()->sync($courseIds);

        try {
            $mailData = collect($data);
            $mailData->subject = 'Ваша заявка на обучение принята';
            $mailData->template = 'mails.template';
            Mail::to($data['email'])->send(new SendEmail($mailData));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('org.index');
    }
}
