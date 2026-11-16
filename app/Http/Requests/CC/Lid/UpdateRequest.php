<?php

namespace App\Http\Requests\CC\Lid;

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
        $responsibleId = $this->input('responsible_id');

        $this->merge([
            'type' => $this->has('type') ? $this->type : '',
            'responsible_id' => ($responsibleId === '' || $responsibleId === null)
                ? null
                : $responsibleId,
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
            'lastname' => '',
            'firstname' => '',
            'email' => '',
            'category_id' => '',
            'region_id' => '',
            'course_id' => '',
            'lid_level_edu_id' => '',
            'status_id' => 'required|string',
            'responsible_id' => 'nullable|integer|exists:users,id',
            'type' => 'nullable|string',
        ];
    }
}
