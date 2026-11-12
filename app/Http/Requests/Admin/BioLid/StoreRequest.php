<?php

namespace App\Http\Requests\Admin\BioLid;

use App\Models\BioLid;
use App\Models\Org;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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

        $studyGroupId = $this->input('biolid_study_group_id');

        $this->merge([
            'biolid_study_group_id' => ($studyGroupId === '' || $studyGroupId === null) ? null : $studyGroupId,
            'biolid_auto_create_user' => (int) $this->input('biolid_auto_create_user') === 1 ? 1 : 0,
            'biolid_send_confirmation_email' => (int) $this->input('biolid_send_confirmation_email') === 1 ? 1 : 0,
            'biolid_send_credentials_email' => (int) $this->input('biolid_send_credentials_email') === 1 ? 1 : 0,
        ]);
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
            'passport_series' => ['nullable', 'regex:/^\d{2} \d{2}$/'],
            'passport_number' => ['nullable', 'regex:/^\d{6}$/'],
            'passport_issued_by' => 'nullable|string|max:255',
            'passport_issued_at' => 'nullable|date|before_or_equal:today',
            'passport_department_code' => ['nullable', 'regex:/^\d{3}–\d{3}$/u'],
            'education' => 'nullable|string|max:5000',
            'education_organization' => 'nullable|string|max:255',
            'education_qualification' => 'nullable|string|max:255',
            'diploma_series_number' => 'nullable|string|max:100',
            'diploma_issued_at' => 'nullable|date|before_or_equal:today',
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
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('bio_lids', 'email')
                    ->where(fn ($query) => $query
                        ->where('course_id', $this->input('course_id'))
                        ->whereNull('deleted_at')),
            ],
            'phone' => 'required|string|max:32',
            'status_id' => 'required|integer|exists:statuses,id',
            'responsible_id' => 'nullable|integer|exists:users,id',
            'biolid_study_group_id' => [
                'nullable',
                'integer',
                Rule::exists('study_groups', 'id')->where(function ($query) {
                    $courseId = $this->input('course_id');
                    if ($courseId) {
                        $query->where('course_id', $courseId);
                    }
                    $query->whereNull('deleted_at');
                }),
            ],
            'biolid_auto_create_user' => 'nullable|boolean',
            'biolid_send_confirmation_email' => 'nullable|boolean',
            'biolid_send_credentials_email' => 'nullable|boolean',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $needsUser = $this->filled('biolid_study_group_id')
                || (int) $this->input('biolid_send_credentials_email') === 1;

            if ($needsUser && (int) $this->input('biolid_auto_create_user') !== 1) {
                $validator->errors()->add(
                    'biolid_auto_create_user',
                    'Для учебной группы или письма с доступом нужно включить создание пользователя'
                );
            }
        });
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
            'email.unique' => 'Заявка с этой почтой на выбранный курс уже есть',
            'phone.required' => 'Укажите номер телефона',
            'status_id.required' => 'Укажите статус',
            'status_id.exists' => 'Выбранный статус недоступен',
            'responsible_id.exists' => 'Выбранный сотрудник недоступен',
            'biolid_study_group_id.exists' => 'Выберите учебную группу выбранного курса',
        ];
    }
}
