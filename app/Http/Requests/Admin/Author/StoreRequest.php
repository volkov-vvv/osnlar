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

        $this->merge([
            'email' => trim((string) $this->input('email')) === '' ? null : trim((string) $this->input('email')),
            'phone' => $digits === '' || $digits === '7' ? null : $phone,
        ]);
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
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Введите телефон в формате +7 (999) 999-99-99',
        ];
    }
}
