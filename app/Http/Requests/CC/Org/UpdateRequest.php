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
            'organization_title' => 'nullable|string|max:255',
            'organization_full_title' => 'nullable|string|max:255',
            'inn' => 'nullable|string|regex:/^\d{10}(\d{2})?$/',
            'lastname' => 'nullable|string|max:255',
            'firstname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'additional_phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'additional_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'course_id' => 'nullable',
            'region_id' => 'nullable',
            'agent_id' => 'nullable',
            'status_id' => 'nullable',
            'politic' => 'nullable',
            'responsible_id' => 'nullable',
        ];
    }
}
