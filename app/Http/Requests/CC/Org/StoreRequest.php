<?php

namespace App\Http\Requests\CC\Org;

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
            'organization_title' => 'required|string',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => '',
            'phone' => 'required|string',
            'phone_prefix' => 'required|string',
            'email' => 'required|string|email|unique:lids',
            'course_id' => 'required|string',
            'region_id' => 'required|string',
            'agent_id' => 'required|string',
            'status_id' => '',
            'politic' => 'required',
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            // Удаляем из номера телефона код страны
            'phone' => explode('+' . $this->phone_prefix, $this->phone)[1],
        ]);
    }

}
