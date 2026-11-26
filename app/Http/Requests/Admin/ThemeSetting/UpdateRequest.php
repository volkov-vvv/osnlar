<?php

namespace App\Http\Requests\Admin\ThemeSetting;

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
            'new_year_theme_enabled' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'new_year_theme_enabled' => $this->boolean('new_year_theme_enabled'),
        ]);
    }
}
