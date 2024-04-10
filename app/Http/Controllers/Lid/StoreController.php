<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lid\StoreRequest;
use App\Models\Lid;
use Illuminate\Http\Request;
use App\Mail\SendEmail;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        Lid::firstOrCreate($data);

        $mailData = collect($data);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = 'mails.template';
        \Mail::to($data['email'])->send(new SendEmail($mailData));

        return redirect()->route('lid.index');

    }
}
