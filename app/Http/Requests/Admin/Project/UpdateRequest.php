<?php

namespace App\Http\Requests\Admin\Project;

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

    protected function prepareForValidation()
    {
        $shortTitle = trim((string) $this->input('short_title'));
        $contractPhrase = trim((string) $this->input('contract_phrase'));

        $this->merge([
            'short_title' => $shortTitle === ''
                ? null
                : mb_strtoupper($shortTitle, 'UTF-8'),
            'contract_phrase' => $contractPhrase === '' ? null : $contractPhrase,
        ]);
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
            'short_title' => 'required|string|max:5',
            'contract_phrase' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'short_title.required' => 'Укажите краткое название проекта',
            'short_title.max' => 'Краткое название не должно превышать 5 символов',
        ];
    }
}
