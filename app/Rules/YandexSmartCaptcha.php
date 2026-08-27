<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class YandexSmartCaptcha implements Rule
{
    public function passes($attribute, $value)
    {
        $response = Http::timeout(3)->get(
            'https://smartcaptcha.yandexcloud.net/validate',
            [
                'secret' => config('services.yandex.smartcaptcha.key'),
                'token' => $value,
                'ip' => request()->ip(),
            ]
        );

        dd([
            'token' => $value,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }


    public function message()
    {
        return 'Проверка капчи не пройдена.';
    }
}
