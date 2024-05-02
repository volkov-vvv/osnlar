<?php

namespace App\Http\Requests\CC\Org;

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
            'organization_title' => '',
            'lastname' => '',
            'firstname' => '',
            'middlename' => '',
            'phone' => '',
            'email' => '',
            'course_id' => '',
            'region_id' => '',
            'agent_id' => '',
            'status_id' => '',
            'politic' => '',
            'responsible_id' => ''
        ];
    }
}
