<?php

namespace App\Http\Requests\Admin\Year;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $yearId = $this->route('year')?->id ?? $this->route('year');

        return [
            'value' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
                Rule::unique('years', 'value')
                    ->ignore($yearId)
                    ->whereNull('deleted_at'),
            ],
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
            'sort' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_default' => $this->boolean('is_default'),
        ]);
    }
}
