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

        if (!$response->successful()) {
            return false;
        }

        $result = $response->json();

        return ($result['status'] ?? null) === 'ok';
    }


    public function message()
    {
        return 'Проверка капчи не пройдена.';
    }
}
