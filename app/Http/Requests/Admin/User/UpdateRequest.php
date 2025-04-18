<?php

namespace App\Http\Requests\Admin\User;

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
            'lastname' => '',
            'name' => 'required|string',
            'middlename' => '',
            'email' => 'required|string|email|unique:users,email,' . $this->user_id,
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'required|string',
            'company_id' => '',
            'agent_ids' => 'array',
            'utm' => 'array',
            'password' => '',
        ];
    }

    protected function passedValidation()
    {
        $data = $this->validator->getData();
        if(!isset($data['utm'])){
            $this->validator->setData( [
                    'utm' => array(),
                ] + $data);
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Это поле необходимо для заполнения',
            'name.string' => 'Имя должно быть строкой',
            'email.required' => 'Это поле необходимо для заполнения',
            'email.string' => 'Почта должна быть строкой',
            'email.email' => 'Ваша почта должна соответствовать формату email',
            'email.unique' => 'Почта с таким email уже существует',
            'role.required' => 'Это поле необходимо для заполнения',
            'role.string' => 'Роль должно быть строкой',
        ];
    }
}
