<?php

namespace App\Http\Requests\Admin\Course;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isPaid = $this->filled('price');

        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'prev_img' => 'nullable|file',
            'image' => 'nullable|file',
            'utp' => '',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'nullable|integer|exists:authors,id',
            'is_published' => 'nullable',
            'open_registration' => 'nullable',
            'code_future' => 'nullable',
            'bioeconomy' => 'nullable',
            'price' => 'nullable|numeric',
            'starts_at' => 'nullable|date',
            'study_form' => ['nullable', Rule::in(array_keys(Course::STUDY_FORMS))],
            'study_weeks' => 'nullable|integer|min:1|max:104',
            'study_hours' => 'nullable|integer|min:1|max:5000',
            'seo_title' => '',
            'seo_description' => '',
            'series' => '',
            'years' => '',
            'company_id' => 'nullable|integer',
            'fast_pay' => 'nullable',
            'default_status_id' => [
                Rule::requiredIf(fn () => (int) $this->input('is_published') === 1),
                'nullable',
                'integer',
                Rule::exists('statuses', 'id')->where(function ($query) use ($isPaid) {
                    if ($isPaid) {
                        $query->where('type', 'order');
                    } else {
                        $query->whereNull('type');
                    }
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Это поле необходимо для заполнения',
            'title.string' => 'Данные должны соответствовать строчному типу',
            'description.required' => 'Это поле необходимо для заполнения',
            'description.string' => 'Данные должны соответствовать строчному типу',
            'prev_img.required' => 'Это поле необходимо для заполнения',
            'prev_img.file' => 'Необходимо выбрать файл',
            'image.required' => 'Это поле необходимо для заполнения',
            'image.file' => 'Необходимо выбрать файл',
            'author_ids.array' => 'Необходимо отправить массив данных',
            'is_published.integer' => 'должно быть в базе данных',
            'default_status_id.required' => 'Для опубликованного курса нужно выбрать статус по умолчанию',
            'default_status_id.exists' => 'Выбранный статус не подходит для типа курса',
            'starts_at.date' => 'Укажите корректную дату старта',
            'study_form.in' => 'Выберите форму обучения из списка',
            'study_weeks.integer' => 'Укажите количество недель числом',
            'study_weeks.min' => 'Срок обучения не может быть меньше 1 недели',
            'study_weeks.max' => 'Срок обучения не может быть больше 104 недель',
            'study_hours.integer' => 'Укажите количество учебных часов числом',
            'study_hours.min' => 'Количество учебных часов не может быть меньше 1',
            'study_hours.max' => 'Количество учебных часов не может быть больше 5000',
        ];
    }

    protected function prepareForValidation(): void
    {
        $price = $this->input('price');
        $defaultStatusId = $this->input('default_status_id');
        $startsAt = $this->input('starts_at');
        $studyForm = $this->input('study_form');
        $studyWeeks = $this->input('study_weeks');
        $studyHours = $this->input('study_hours');
        $series = $this->input('series');
        $years = $this->input('years');

        $this->merge([
            'price' => ($price === '' || $price === null) ? null : $price,
            'default_status_id' => ($defaultStatusId === '' || $defaultStatusId === null) ? null : $defaultStatusId,
            'starts_at' => ($startsAt === '' || $startsAt === null) ? null : $startsAt,
            'study_form' => ($studyForm === '' || $studyForm === null) ? null : $studyForm,
            'study_weeks' => ($studyWeeks === '' || $studyWeeks === null) ? null : $studyWeeks,
            'study_hours' => ($studyHours === '' || $studyHours === null) ? null : $studyHours,
            'series' => ($series === '' || $series === null) ? null : $series,
            'years' => ($years === '' || $years === null) ? null : $years,
        ]);
    }
}
