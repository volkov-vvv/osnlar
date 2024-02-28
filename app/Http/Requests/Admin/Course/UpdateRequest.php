<?php

namespace App\Http\Requests\Admin\Course;

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
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'prev_img' => 'nullable|file',
            'image' => 'nullable|file',
            'category_id' => 'required|integer|exists:categories,id',
            'author_ids' => 'nullable|array',
            'author_ids.*' => 'nullable|integer|exists:authors,id',
        ];
    }
}
