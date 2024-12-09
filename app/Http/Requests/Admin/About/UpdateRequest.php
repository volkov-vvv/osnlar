<?php

namespace App\Http\Requests\Admin\About;

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
            'about_main' => '',
            'about_management' => '',
            'about_computers' => '',
            'about_all' => '',
            'about_it' => '',
            'about_services' => '',
            'about_tech' => '',
        ];
    }
}
