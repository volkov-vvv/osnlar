<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lid\BioeconomyPersonalStoreRequest;
use App\Mail\SendEmail;
use App\Models\BioLid;
use Illuminate\Support\Facades\Mail;

class StoreBioeconomyPersonalController extends Controller
{
    public function __invoke(BioeconomyPersonalStoreRequest $request)
    {
        $data = $request->safe()->except(['org_id', 'phone_prefix', 'politic']);
        $data['politic'] = true;

        BioLid::create($data);

        try {
            $mailData = collect($data);
            $mailData->subject = 'Ваша заявка на обучение принята';
            $mailData->template = 'mails.bioeconomy';
            Mail::to($data['email'])->send(new SendEmail($mailData));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('lid.index');
    }
}
