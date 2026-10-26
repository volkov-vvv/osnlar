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

    public const BIO_DOCS = [
        'passport' => [
            'title' => 'Скан-копия паспорта',
            'mimes' => 'pdf,jpg,jpeg,png',
        ],
        'education_doc' => [
            'title' => 'Скан-копия документа об образовании',
            'mimes' => 'pdf,jpg,jpeg,png',
        ],
        'inn' => [
            'title' => 'Скан-копия свидетельства ИНН',
            'mimes' => 'pdf,jpg,jpeg,png',
        ],
        'snils' => [
            'title' => 'Скан-копия СНИЛС',
            'mimes' => 'pdf,jpg,jpeg,png',
        ],
    ];

    public const REVIEW_PENDING = 'pending';
    public const REVIEW_APPROVED = 'approved';
    public const REVIEW_REJECTED = 'rejected';

    public const REVIEW_STATUSES = [
        self::REVIEW_PENDING => 'На проверке',
        self::REVIEW_APPROVED => 'Проверено',
        self::REVIEW_REJECTED => 'Отклонено',
    ];

    protected $table = 'user_documents';
    protected $guarded = false;

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bioLid()
    {
        return $this->belongsTo(BioLid::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function reviewStatusLabel(): string
    {
        if ($this->review_status === null) {
            return 'Не отправлено';
        }

        return self::REVIEW_STATUSES[$this->review_status] ?? $this->review_status;
    }

    public function reviewStatusBadgeClass(): string
    {
        return match ($this->review_status) {
            self::REVIEW_APPROVED => 'badge badge-success',
            self::REVIEW_REJECTED => 'badge badge-danger',
            self::REVIEW_PENDING => 'badge badge-warning',
            default => 'badge badge-secondary',
        };
    }

    public function isPendingReview(): bool
    {
        return $this->review_status === self::REVIEW_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->review_status === self::REVIEW_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->review_status === self::REVIEW_REJECTED;
    }
}
