<?php

namespace App\Http\Requests\Admin\BioeconomySetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $file = 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:131072';

        return [
            'presentation' => $file,
            'order' => $file,
            'organizations' => $file,
            'remove_presentation' => 'nullable|boolean',
            'remove_order' => 'nullable|boolean',
            'remove_organizations' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'presentation.file' => 'Презентация проекта должна быть файлом',
            'presentation.mimes' => 'Презентация: допустимы pdf, doc, docx, ppt, pptx, xls, xlsx',
            'presentation.max' => 'Презентация не должна быть больше 128 МБ',
            'order.file' => 'Приказ должен быть файлом',
            'order.mimes' => 'Приказ: допустимы pdf, doc, docx, ppt, pptx, xls, xlsx',
            'order.max' => 'Приказ не должен быть больше 128 МБ',
            'organizations.file' => 'Список программ должен быть файлом',
            'organizations.mimes' => 'Список программ: допустимы pdf, doc, docx, ppt, pptx, xls, xlsx',
            'organizations.max' => 'Список программ не должен быть больше 128 МБ',
        ];
    }
}
