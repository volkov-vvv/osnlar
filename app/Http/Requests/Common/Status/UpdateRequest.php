<?php

namespace App\Http\Requests\Common\Status;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'color' => 'string',
            'description' => 'string',
        ];
    }
}
