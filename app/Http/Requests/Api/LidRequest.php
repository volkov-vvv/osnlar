<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LidRequest extends FormRequest
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
            'data' => '',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:lids',
            'course_id' => 'required|string',
            'lid_level_edu_id' => 'string',
            'region_id' => 'required|string',
            'category_id' => '',
            'company_id' => 'required|string',
            'politic' => 'required',
            'in_project' => '',
            'utm_source' => '',
            'utm_medium' => '',
            'utm_campaign' => '',
            'utm_term' => '',
            'utm_content' => '',
            'created_at' => 'nullable|string',
            'responsible_id' => 'nullable',
        ];
    }
}
