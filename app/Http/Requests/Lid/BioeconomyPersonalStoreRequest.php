<?php

namespace App\Http\Requests\Lid;

use App\Models\BioLid;
use App\Models\Org;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BioeconomyPersonalStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $nullable = [
            'middlename',
            'citizenship',
            'residence_address',
            'education',
            'position',
            'snils',
            'gender',
            'birth_date',
        ];

        $merged = [];
        foreach ($nullable as $field) {
            $value = trim((string) $this->input($field));
            $merged[$field] = $value === '' ? null : $value;
        }

        $snilsDigits = preg_replace('/\D+/', '', (string) ($merged['snils'] ?? ''));
        if ($snilsDigits === '') {
            $merged['snils'] = null;
        } elseif (strlen($snilsDigits) === 11) {
            $merged['snils'] = substr($snilsDigits, 0, 3).'-'
                .substr($snilsDigits, 3, 3).'-'
                .substr($snilsDigits, 6, 3).' '
                .substr($snilsDigits, 9, 2);
        } else {
            $merged['snils'] = $snilsDigits;
        }

        $org = Org::query()
            ->where('source', 'bioeconomy')
            ->where('is_published', true)
            ->whereKey($this->input('org_id'))
            ->first();

        if ($org) {
            $merged['organization_title'] = trim((string) $org->organization_title);
            $innDigits = preg_replace('/\D+/', '', (string) $org->inn);
            $merged['organization_inn'] = $innDigits === '' ? null : $innDigits;
        } else {
            $merged['organization_title'] = null;
            $merged['organization_inn'] = null;
        }

        $merged['citizenship'] = 'РФ';

        $phone = (string) $this->input('phone');
        $prefix = (string) $this->input('phone_prefix');
        if ($prefix !== '' && str_contains($phone, '+'.$prefix)) {
            $parts = explode('+'.$prefix, $phone, 2);
            $phone = $parts[1] ?? $phone;
        }
        $merged['phone'] = preg_replace('/\D+/', '', $phone);
        $merged['phone_prefix'] = $prefix !== '' ? $prefix : '7';

        $this->merge($merged);
    }

    public function rules()
    {
        return [
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) {
                    $query->where('is_published', 1)->where('bioeconomy', 1);
                }),
            ],
            'org_id' => [
                'required',
                'integer',
                Rule::exists('orgs', 'id')->where(function ($query) {
                    $query->where('source', 'bioeconomy')
                        ->where('is_published', true)
                        ->whereNull('deleted_at');
                }),
            ],
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => ['required', Rule::in(array_keys(BioLid::GENDERS))],
            'snils' => ['required', 'regex:/^\d{3}-\d{3}-\d{3} \d{2}$/'],
            'citizenship' => 'required|string|max:255',
            'residence_address' => 'required|string|max:2000',
            'education' => 'required|string|max:5000',
            'organization_title' => 'required|string|max:255',
            'organization_inn' => ['nullable', 'regex:/^\d{10}$|^\d{12}$/'],
            'position' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_prefix' => 'required|string|max:8',
            'phone' => 'required|string|max:32',
            'politic' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'Курс не указан',
            'course_id.exists' => 'Выбранный курс недоступен',
            'org_id.required' => 'Выберите организацию',
            'org_id.exists' => 'Выбранная организация недоступна',
            'lastname.required' => 'Укажите фамилию',
            'firstname.required' => 'Укажите имя',
            'birth_date.required' => 'Укажите дату рождения',
            'birth_date.before' => 'Дата рождения должна быть в прошлом',
            'gender.required' => 'Укажите пол',
            'gender.in' => 'Выберите пол из списка',
            'snils.required' => 'Укажите СНИЛС',
            'snils.regex' => 'СНИЛС должен быть в формате 111-222-333 00',
            'citizenship.required' => 'Укажите гражданство',
            'residence_address.required' => 'Укажите адрес места жительства',
            'education.required' => 'Укажите сведения об образовании',
            'organization_title.required' => 'Выберите организацию',
            'organization_inn.regex' => 'ИНН организации должен содержать 10 или 12 цифр',
            'position.required' => 'Укажите должность',
            'email.required' => 'Укажите email',
            'email.email' => 'Укажите корректный email',
            'phone.required' => 'Укажите номер телефона',
            'phone_prefix.required' => 'Укажите код страны телефона',
            'politic.accepted' => 'Необходимо согласие с политикой обработки персональных данных',
        ];
    }
}
