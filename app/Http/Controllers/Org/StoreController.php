<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Http\Requests\CC\Org\StoreRequest;
use App\Models\Org;
use Illuminate\Http\Request;
use App\Mail\SendEmail;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        Org::firstOrCreate($data);

        $mailData = collect($data);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = 'mails.template';
        \Mail::to($data['email'])->send(new SendEmail($mailData));

        return redirect()->route('org.index');

    }
}
