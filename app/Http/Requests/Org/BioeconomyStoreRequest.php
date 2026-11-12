<?php

namespace App\Http\Requests\Org;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BioeconomyStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'organization_title' => 'required|string|max:255',
            'inn' => 'required|string|regex:/^\d{10}(\d{2})?$/',
            'appointment_letter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'phone' => 'required|string',
            'phone_prefix' => 'required|string',
            'email' => 'required|string|email|max:255',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => [
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) {
                    $query->where('bioeconomy', 1)->where('is_published', 1);
                }),
            ],
            'comment' => 'nullable|string|max:5000',
            'politic' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'lastname.required' => 'Укажите фамилию',
            'firstname.required' => 'Укажите имя',
            'organization_title.required' => 'Укажите название организации',
            'inn.required' => 'Укажите ИНН организации',
            'inn.regex' => 'ИНН должен содержать 10 или 12 цифр',
            'appointment_letter.required' => 'Загрузите письмо о назначении ответственного лица',
            'appointment_letter.file' => 'Загрузите файл письма о назначении ответственного лица',
            'appointment_letter.mimes' => 'Допустимые форматы письма: PDF, JPG, PNG',
            'appointment_letter.max' => 'Размер файла не должен превышать 5 МБ',
            'phone.required' => 'Укажите телефон',
            'email.required' => 'Укажите электронную почту',
            'email.email' => 'Укажите корректный email',
            'course_ids.required' => 'Выберите хотя бы один курс',
            'course_ids.min' => 'Выберите хотя бы один курс',
            'politic.required' => 'Необходимо согласие на обработку персональных данных',
            'politic.accepted' => 'Необходимо согласие на обработку персональных данных',
        ];
    }

    protected function prepareForValidation()
    {
        $phone = (string) $this->phone;
        $prefix = (string) $this->phone_prefix;

        if ($prefix !== '' && str_contains($phone, '+' . $prefix)) {
            $parts = explode('+' . $prefix, $phone, 2);
            $phone = $parts[1] ?? $phone;
        }

        $this->merge([
            'phone' => preg_replace('/\D+/', '', $phone),
            'phone_prefix' => $prefix !== '' ? $prefix : '7',
        ]);
    }
}
