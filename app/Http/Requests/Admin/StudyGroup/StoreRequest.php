<?php

namespace App\Http\Requests\Admin\StudyGroup;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'short_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'nullable|integer|exists:courses,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Это поле необходимо для заполнения',
            'title.string' => 'Полное название должно быть строкой',
            'ends_at.after_or_equal' => 'Дата завершения не может быть раньше даты начала',
        ];
    }
}
