<?php

namespace App\Http\Requests\Admin\Author;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function prepareForValidation()
    {
        $phone = trim((string) $this->input('phone'));
        $digits = preg_replace('/\D+/', '', $phone);

        $nullableStrings = [
            'email',
            'passport_series',
            'passport_number',
            'passport_issued_by',
            'passport_department_code',
            'inn',
            'registration_address',
            'bank_account',
            'bank_name',
            'bank_bik',
            'bank_correspondent_account',
            'passport_issued_at',
        ];

        $merged = [
            'phone' => $digits === '' || $digits === '7' ? null : $phone,
        ];

        foreach ($nullableStrings as $field) {
            $value = trim((string) $this->input($field));
            $merged[$field] = $value === '' ? null : $value;
        }

        $seriesDigits = preg_replace('/\D+/', '', (string) ($merged['passport_series'] ?? ''));
        if ($seriesDigits === '') {
            $merged['passport_series'] = null;
        } elseif (strlen($seriesDigits) === 4) {
            $merged['passport_series'] = substr($seriesDigits, 0, 2).' '.substr($seriesDigits, 2, 2);
        } else {
            $merged['passport_series'] = $seriesDigits;
        }

        $numberDigits = preg_replace('/\D+/', '', (string) ($merged['passport_number'] ?? ''));
        $merged['passport_number'] = $numberDigits === '' ? null : $numberDigits;

        $departmentDigits = preg_replace('/\D+/', '', (string) ($merged['passport_department_code'] ?? ''));
        if ($departmentDigits === '') {
            $merged['passport_department_code'] = null;
        } elseif (strlen($departmentDigits) === 6) {
            $merged['passport_department_code'] = substr($departmentDigits, 0, 3).'–'.substr($departmentDigits, 3, 3);
        } else {
            $merged['passport_department_code'] = $departmentDigits;
        }

        $bikDigits = preg_replace('/\D+/', '', (string) ($merged['bank_bik'] ?? ''));
        $merged['bank_bik'] = $bikDigits === '' ? null : $bikDigits;

        $correspondentDigits = preg_replace('/\D+/', '', (string) ($merged['bank_correspondent_account'] ?? ''));
        $merged['bank_correspondent_account'] = $correspondentDigits === '' ? null : $correspondentDigits;

        $this->merge($merged);
    }

    public function rules()
    {
        return [
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => ['nullable', 'regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
            'passport_series' => ['nullable', 'regex:/^\d{2} \d{2}$/'],
            'passport_number' => ['nullable', 'regex:/^\d{6}$/'],
            'passport_issued_by' => 'nullable|string|max:255',
            'passport_issued_at' => 'nullable|date',
            'passport_department_code' => ['nullable', 'regex:/^\d{3}–\d{3}$/u'],
            'inn' => ['nullable', 'regex:/^\d{10}$|^\d{12}$/'],
            'registration_address' => 'nullable|string|max:2000',
            'bank_account' => 'nullable|string|max:34',
            'bank_name' => 'nullable|string|max:255',
            'bank_bik' => ['nullable', 'regex:/^\d{9}$/'],
            'bank_correspondent_account' => ['nullable', 'regex:/^\d{20}$/'],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Введите телефон в формате +7 (999) 999-99-99',
            'passport_series.regex' => 'Серия паспорта должна быть в формате XX XX (4 цифры)',
            'passport_number.regex' => 'Номер паспорта должен содержать 6 цифр',
            'passport_department_code.regex' => 'Код подразделения должен быть в формате ***–*** (6 цифр)',
            'bank_bik.regex' => 'БИК должен содержать 9 цифр',
            'bank_correspondent_account.regex' => 'Корреспондентский счёт должен содержать 20 цифр',
            'inn.regex' => 'ИНН должен содержать 10 или 12 цифр',
        ];
    }
}
