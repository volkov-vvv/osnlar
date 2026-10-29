<?php

namespace App\Http\Requests\Admin\BioLid;

use App\Models\BioLid;
use App\Models\Org;
use Illuminate\Validation\Rule;

class StoreRequest extends UpdateRequest
{
    protected function prepareForValidation()
    {
        parent::prepareForValidation();

        $org = Org::query()
            ->where('source', 'bioeconomy')
            ->where('is_published', true)
            ->whereKey($this->input('org_id'))
            ->first();

        if ($org) {
            $innDigits = preg_replace('/\D+/', '', (string) $org->inn);

            $this->merge([
                'organization_title' => trim((string) $org->organization_title),
                'organization_inn' => $innDigits === '' ? null : $innDigits,
            ]);
        }
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
            'birth_date' => 'nullable|date|before:today',
            'gender' => ['nullable', Rule::in(array_keys(BioLid::GENDERS))],
            'snils' => ['nullable', 'regex:/^\d{3}-\d{3}-\d{3} \d{2}$/'],
            'citizenship' => 'nullable|string|max:255',
            'residence_address' => 'nullable|string|max:2000',
            'education' => 'nullable|string|max:5000',
            'org_id' => [
                'nullable',
                'integer',
                Rule::exists('orgs', 'id')->where(function ($query) {
                    $query->where('source', 'bioeconomy')
                        ->where('is_published', true)
                        ->whereNull('deleted_at');
                }),
            ],
            'organization_title' => 'nullable|string|max:255',
            'organization_inn' => ['nullable', 'regex:/^\d{10}$|^\d{12}$/'],
            'position' => 'nullable|string|max:255',
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
            'birth_date.before' => 'Дата рождения должна быть в прошлом',
            'gender.in' => 'Выберите пол из списка',
            'snils.regex' => 'СНИЛС должен быть в формате 111-222-333 00',
            'org_id.exists' => 'Выбранная организация недоступна',
            'organization_inn.regex' => 'ИНН организации должен содержать 10 или 12 цифр',
            'email.required' => 'Укажите email',
            'email.email' => 'Укажите корректный email',
            'phone.required' => 'Укажите номер телефона',
            'status_id.required' => 'Укажите статус',
            'status_id.exists' => 'Выбранный статус недоступен',
            'responsible_id.exists' => 'Выбранный сотрудник недоступен',
        ];
    }
}
