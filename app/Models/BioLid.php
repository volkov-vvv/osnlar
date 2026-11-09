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

    public const ARPM_NOT_SENT = 'not_sent';

    public const ARPM_UNDER_REVIEW = 'under_review';

    public const ARPM_APPROVED = 'approved';

    public const ARPM_REMARKS = 'remarks';

    public const ARPM_STATUSES = [
        self::ARPM_NOT_SENT => 'Не отправлено',
        self::ARPM_UNDER_REVIEW => 'На рассмотрении',
        self::ARPM_APPROVED => 'Утверждено',
        self::ARPM_REMARKS => 'Замечания',
    ];

    public const ARPM_STATUS_ICONS = [
        self::ARPM_NOT_SENT => 'fas fa-minus-circle',
        self::ARPM_UNDER_REVIEW => 'fas fa-hourglass-half',
        self::ARPM_APPROVED => 'fas fa-check-circle',
        self::ARPM_REMARKS => 'fas fa-exclamation-triangle',
    ];

    protected $table = 'bio_lids';

    protected $guarded = false;

    protected $attributes = [
        'arpm_status' => self::ARPM_NOT_SENT,
    ];

    protected $casts = [
        'birth_date' => 'date',
        'passport_issued_at' => 'date',
        'diploma_issued_at' => 'date',
        'politic' => 'boolean',
        'data_submitted_at' => 'datetime',
        'data_reviewed_at' => 'datetime',
    ];

    public function hasPassportData(): bool
    {
        return trim((string) $this->passport_series) !== ''
            && trim((string) $this->passport_number) !== ''
            && trim((string) $this->passport_issued_by) !== ''
            && $this->passport_issued_at !== null
            && trim((string) $this->passport_department_code) !== '';
    }

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

    /**
     * Шаги 1–5 (личные данные + обязательные сканы) проверены — можно открывать заявление и согласия.
     */
    public function areInitialDocumentStepsApproved(): bool
    {
        if (! $this->isDataApproved()) {
            return false;
        }

        $this->loadMissing('documents');

        $docsByType = $this->documents
            ->sortByDesc('id')
            ->unique('type')
            ->keyBy('type');

        foreach (array_keys(UserDocument::BIO_DOCS) as $type) {
            $document = $docsByType->get($type);
            if (! $document || ! $document->isApproved()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Агрегированный статус проверки данных и документов ЛК.
     *
     * @return array{status: string, label: string, badge: string}
     */
    public function documentsReviewSummary(): array
    {
        $this->loadMissing('documents');

        $statuses = [$this->data_review_status];

        $docsByType = $this->documents
            ->sortByDesc('id')
            ->unique('type')
            ->keyBy('type');

        foreach (UserDocument::requiredBioDocTypes() as $type) {
            $document = $docsByType->get($type);
            $statuses[] = $document?->review_status;
        }

        $nameChange = $docsByType->get('name_change_doc');
        if ($nameChange) {
            $statuses[] = $nameChange->review_status;
        }

        if (in_array(UserDocument::REVIEW_PENDING, $statuses, true)) {
            return [
                'status' => UserDocument::REVIEW_PENDING,
                'label' => 'На проверке',
                'badge' => 'badge badge-warning',
            ];
        }

        if (in_array(UserDocument::REVIEW_REJECTED, $statuses, true)) {
            return [
                'status' => UserDocument::REVIEW_REJECTED,
                'label' => 'Отклонено',
                'badge' => 'badge badge-danger',
            ];
        }

        $allApproved = $statuses !== []
            && collect($statuses)->every(
                static fn ($status) => $status === UserDocument::REVIEW_APPROVED
            );

        if ($allApproved) {
            return [
                'status' => UserDocument::REVIEW_APPROVED,
                'label' => 'Проверено',
                'badge' => 'badge badge-success',
            ];
        }

        return [
            'status' => 'empty',
            'label' => 'Не отправлено',
            'badge' => 'badge badge-secondary',
        ];
    }

    public function arpmStatusValue(): string
    {
        $status = (string) ($this->arpm_status ?: self::ARPM_NOT_SENT);

        return array_key_exists($status, self::ARPM_STATUSES) ? $status : self::ARPM_NOT_SENT;
    }

    public function arpmStatusLabel(): string
    {
        return self::ARPM_STATUSES[$this->arpmStatusValue()];
    }

    public function arpmStatusIconClass(): string
    {
        return self::arpmStatusIconClassFor($this->arpmStatusValue());
    }

    public static function arpmStatusIconClassFor(string $status): string
    {
        return self::ARPM_STATUS_ICONS[$status] ?? self::ARPM_STATUS_ICONS[self::ARPM_NOT_SENT];
    }

    public function arpmStatusBadgeClass(): string
    {
        return match ($this->arpmStatusValue()) {
            self::ARPM_UNDER_REVIEW => 'badge badge-warning',
            self::ARPM_APPROVED => 'badge badge-success',
            self::ARPM_REMARKS => 'badge badge-danger',
            default => 'badge badge-secondary',
        };
    }

    /**
     * Если все документы проверены, а статус АРПМ ещё «Не отправлено» — ставим «На рассмотрении».
     */
    public function syncArpmStatusWhenDocumentsApproved(): bool
    {
        if ($this->documentsReviewSummary()['status'] !== UserDocument::REVIEW_APPROVED) {
            return false;
        }

        if ($this->arpmStatusValue() !== self::ARPM_NOT_SENT) {
            return false;
        }

        $this->forceFill([
            'arpm_status' => self::ARPM_UNDER_REVIEW,
        ])->save();

        return true;
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


