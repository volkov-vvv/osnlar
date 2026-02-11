<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class UserDocument extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const FIRST_STEP_DOCS = [
        'consent_personal_data' => [
            'title' => 'Согласие на обработку персональных данных',
            'mimes' => 'pdf,jpg,png',
        ],
        'passport' => [
            'title' => 'Паспорт',
            'mimes' => 'pdf,jpg,png',
        ],
        'inn' => [
            'title' => 'ИНН',
            'mimes' => 'pdf,jpg,png',
        ],
        'snils' => [
            'title' => 'СНИЛС',
            'mimes' => 'pdf,jpg,png',
        ],
        'education_doc' => [
            'title' => 'Документ об образовании',
            'mimes' => 'pdf,jpg,png',
        ],
        'information_enrollment' => [
            'title' => 'Сведения о зачислении',
            'mimes' => 'xls,xlsx',
        ],
    ];

    protected $table = 'user_documents';
    protected $guarded = false;
}
