<?php

namespace App\Http\Requests\Admin\BioLid;

use App\Models\BioLid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'organization_title',
            'organization_inn',
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

        $innDigits = preg_replace('/\D+/', '', (string) ($merged['organization_inn'] ?? ''));
        $merged['organization_inn'] = $innDigits === '' ? null : $innDigits;

        $phone = preg_replace('/\D+/', '', (string) $this->input('phone')) ?? '';
        if (
            (str_starts_with($phone, '8') || str_starts_with($phone, '7'))
            && strlen($phone) === 11
        ) {
            $phone = substr($phone, 1);
        }
        $merged['phone'] = $phone;

        $responsibleId = $this->input('responsible_id');
        $merged['responsible_id'] = ($responsibleId === '' || $responsibleId === null)
            ? null
            : $responsibleId;

        $this->merge($merged);
    }

    public function rules()
    {
        return [
            'course_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) {
                    $query->where('bioeconomy', 1);
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
            'phone' => 'required|string|max:32',
            'status_id' => 'required|integer|exists:statuses,id',
            'responsible_id' => 'nullable|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'Выберите курс',
            'course_id.exists' => 'Выбранный курс недоступен',
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
            'organization_title.required' => 'Укажите организацию',
            'organization_inn.regex' => 'ИНН организации должен содержать 10 или 12 цифр',
            'position.required' => 'Укажите должность',
            'email.required' => 'Укажите email',
            'email.email' => 'Укажите корректный email',
            'phone.required' => 'Укажите номер телефона',
            'status_id.required' => 'Укажите статус',
            'status_id.exists' => 'Выбранный статус недоступен',
            'responsible_id.exists' => 'Выбранный сотрудник недоступен',
        ];
    }
}
