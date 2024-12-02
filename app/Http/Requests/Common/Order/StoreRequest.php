<?php

namespace App\Http\Requests\Common\Order;

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
            'middlename' => 'string|nullable',
            'phone_prefix' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email',
            'politic' => 'required',
            'course_id' => 'required|numeric',
            'region_id' => 'numeric|nullable',
            'responsible_id' => 'numeric|nullable',
        ];
    }
}
