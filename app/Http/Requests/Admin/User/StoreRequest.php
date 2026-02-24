<?php

namespace App\Http\Requests\Admin\User;

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
            'lastname' => '',
            'name' => 'required|string',
            'middlename' => '',
            'email' => 'required|string|email|unique:users',
            'phone_prefix' => 'string',
            'phone' => 'string',
            'password' => 'required|string',
            'role' => 'required|string',
            'company_id' => '',
        ];
    }

    protected function passedValidation() {
        $data = $this->validator->getData();

        $this->validator->setData( [
                'phone' => explode('+' . $data['phone_prefix'], $data['phone'])[1],
            ] + $data);
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
            'password.required' => 'Это поле необходимо для заполнения',
            'password.string' => 'Почта должна быть строкой',
            'role.required' => 'Это поле необходимо для заполнения',
            'role.string' => 'Роль должно быть строкой',
        ];
    }
}
