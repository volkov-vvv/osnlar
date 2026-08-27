<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class YandexSmartCaptcha implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {

        $response = Http::timeout(3)->get(
            'https://smartcaptcha.yandexcloud.net/validate',
            [
                'secret' => config('services.yandex.smartcaptcha.key'),
                'token' => $value,
                'ip' => request()->ip(),
            ]
        );

        // Если сервер капчи недоступен
        if (!$response->successful()) {
            // Вариант как у Яндекса:
            // разрешаем доступ при ошибке сервера
            return;
        }

        $result = $response->json();

        if (($result['status'] ?? null) !== 'ok') {
            $fail('Проверка капчи не пройдена.');
        }
    }
}
