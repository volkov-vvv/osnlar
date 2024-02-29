<?php

namespace App\Http\Requests\Admin\Course;

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
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'prev_img' => 'required|file',
            'image' => 'required|file',
            'category_id' => 'required|integer|exists:categories,id',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'nullable|integer|exists:authors,id',
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
            'category_id.required' => 'Это поле необходимо для заполнения',
            'category_id.integer' => 'ID категории должно быть числом',
            'category_id.exists' => 'ID категории должно быть в базе данных',
            'author_ids.array' => 'Необходимо отправить массив данных',

        ];
    }

}
