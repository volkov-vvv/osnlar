<?php

namespace App\Http\Requests\User\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'lastname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'lastname.required' => 'Укажите фамилию',
            'lastname.string' => 'Фамилия должна быть строкой',
            'lastname.max' => 'Фамилия не должна быть длиннее 255 символов',
            'name.required' => 'Укажите имя',
            'name.string' => 'Имя должно быть строкой',
            'name.max' => 'Имя не должно быть длиннее 255 символов',
            'avatar.image' => 'Аватар должен быть изображением',
            'avatar.mimes' => 'Допустимые форматы: jpg, jpeg, png, webp',
            'avatar.max' => 'Размер аватарки не должен превышать 2 МБ',
        ];
    }
}
