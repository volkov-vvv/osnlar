<?php

namespace App\Http\Requests\Admin\Author;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'passport_series' => 'nullable|string|max:10',
            'passport_number' => 'nullable|string|max:20',
            'passport_issued_by' => 'nullable|string|max:255',
            'passport_issued_at' => 'nullable|date',
            'passport_department_code' => 'nullable|string|max:20',
            'inn' => ['nullable', 'regex:/^\d{10}$|^\d{12}$/'],
            'registration_address' => 'nullable|string|max:2000',
            'bank_account' => 'nullable|string|max:34',
            'bank_name' => 'nullable|string|max:255',
            'bank_bik' => 'nullable|string|max:20',
            'bank_correspondent_account' => 'nullable|string|max:34',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Введите телефон в формате +7 (999) 999-99-99',
            'inn.regex' => 'ИНН должен содержать 10 или 12 цифр',
        ];
    }
}
