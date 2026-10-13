<?php

namespace App\Http\Requests\Admin\FormSetting;

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
            'lid_captcha_enabled' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'lid_captcha_enabled' => $this->boolean('lid_captcha_enabled'),
        ]);
    }
}
