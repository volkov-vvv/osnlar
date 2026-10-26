<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BioLid extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const GENDERS = [
        'male' => 'Мужской',
        'female' => 'Женский',
    ];

    protected $table = 'bio_lids';

    protected $guarded = false;

    protected $casts = [
        'birth_date' => 'date',
        'politic' => 'boolean',
        'data_submitted_at' => 'datetime',
        'data_reviewed_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function dataReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'data_reviewed_by');
    }

    public function dataReviewStatusLabel(): string
    {
        if ($this->data_review_status === null) {
            return 'Не отправлено';
        }

        return UserDocument::REVIEW_STATUSES[$this->data_review_status] ?? $this->data_review_status;
    }

    public function dataReviewStatusBadgeClass(): string
    {
        return match ($this->data_review_status) {
            UserDocument::REVIEW_APPROVED => 'badge badge-success',
            UserDocument::REVIEW_REJECTED => 'badge badge-danger',
            UserDocument::REVIEW_PENDING => 'badge badge-warning',
            default => 'badge badge-secondary',
        };
    }

    public function isDataPendingReview(): bool
    {
        return $this->data_review_status === UserDocument::REVIEW_PENDING;
    }

    public function isDataApproved(): bool
    {
        return $this->data_review_status === UserDocument::REVIEW_APPROVED;
    }

    public function isDataRejected(): bool
    {
        return $this->data_review_status === UserDocument::REVIEW_REJECTED;
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->lastname,
            $this->firstname,
            $this->middlename,
        ])));
    }

    public function genderLabel(): ?string
    {
        if (! is_string($this->gender)) {
            return null;
        }

        return self::GENDERS[$this->gender] ?? $this->gender;
    }

    public function genderShortLabel(): ?string
    {
        return match ($this->gender) {
            'male' => 'Муж',
            'female' => 'Жен',
            default => $this->genderLabel(),
        };
    }

    /**
     * Номер для отображения: как у заявок, с ведущей «8».
     */
    public function phoneDisplay(): string
    {
        $phone = preg_replace('/\D+/', '', (string) $this->phone) ?? '';
        if ($phone === '') {
            return '';
        }

        if (str_starts_with($phone, '8')) {
            return $phone;
        }

        if (str_starts_with($phone, '7') && strlen($phone) >= 11) {
            return '8'.substr($phone, 1);
        }

        return '8'.$phone;
    }

    /**
     * Номер в формате 8-900-300-05-00.
     */
    public function phoneFormatted(): string
    {
        $phone = $this->phoneDisplay();
        if (strlen($phone) !== 11) {
            return $phone;
        }

        return substr($phone, 0, 1).'-'
            .substr($phone, 1, 3).'-'
            .substr($phone, 4, 3).'-'
            .substr($phone, 7, 2).'-'
            .substr($phone, 9, 2);
    }
}


