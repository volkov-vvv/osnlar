<?php

namespace App\Http\Requests\Admin\Lid;

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
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => '',
            'data' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:lids',
            'course_id' => 'required|string',
            'lid_level_edu_id' => 'required|string',
            'region_id' => 'required|string',
            'category_id' => 'required|string',
            'agent_id' => 'required|string',
            'politic' => 'required',
            'in_project' => '',
        ];
    }
}
