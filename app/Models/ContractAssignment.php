<?php

namespace App\Models;

use App\Models\Concerns\HasScannedPdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use morphos\Russian\CardinalNumeralGenerator;
use morphos\Russian\Cases;

class ContractAssignment extends Model
{
    use HasFactory;
    use HasScannedPdf;
    use SoftDeletes;

    public const WORK_TYPE_LECTURES = 1;
    public const WORK_TYPE_NOTEBOOK = 2;
    public const WORK_TYPE_CONSULTING = 3;
    public const WORK_TYPE_ATTESTATION = 4;
    public const WORK_TYPE_CUSTOM = 5;

    public const PAYMENT_HOURLY = 'hourly';
    public const PAYMENT_FIXED = 'fixed';
    public const PAYMENT_NONE = 'none';

    protected $table = 'contract_assignments';
    protected $guarded = false;

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'per_student_amount' => 'decimal:2',
        'per_student_enabled' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'issued_at' => 'date',
        'work_type_codes' => 'array',
    ];

    /**
     * Основная оплата (без «за слушателя»).
     *
     * @return array<string, string>
     */
    public static function basePaymentTypeLabels(): array
    {
        return [
            self::PAYMENT_HOURLY => 'Оплата по часам',
            self::PAYMENT_FIXED => 'Фиксированная оплата',
            self::PAYMENT_NONE => 'Нет основной оплаты',
        ];
    }

    /**
     * @return list<string>
     */
    public static function basePaymentTypeKeys(): array
    {
        return array_keys(self::basePaymentTypeLabels());
    }

    public function basePaymentType(): string
    {
        $type = $this->payment_type ?: self::PAYMENT_HOURLY;

        return in_array($type, self::basePaymentTypeKeys(), true) ? $type : self::PAYMENT_HOURLY;
    }

    public function isPaymentHourly(): bool
    {
        return $this->basePaymentType() === self::PAYMENT_HOURLY;
    }

    public function isPaymentFixed(): bool
    {
        return $this->basePaymentType() === self::PAYMENT_FIXED;
    }

    public function hasBasePayment(): bool
    {
        return $this->basePaymentType() !== self::PAYMENT_NONE;
    }

    public function isPaymentPerStudent(): bool
    {
        return (bool) $this->per_student_enabled;
    }

    public function paymentTypeLabel(): string
    {
        $parts = [];

        if ($this->isPaymentHourly()) {
            $parts[] = 'Оплата по часам';
        } elseif ($this->isPaymentFixed()) {
            $parts[] = 'Фиксированная оплата';
        }

        if ($this->isPaymentPerStudent()) {
            $parts[] = 'за успешно обученного';
        }

        return $parts !== [] ? implode(' + ', $parts) : '—';
    }

    public function paymentAmountShortLabel(): string
    {
        $parts = [];

        if ($this->isPaymentHourly()) {
            $parts[] = $this->hourlyRateFormatted().' руб./час';
        } elseif ($this->isPaymentFixed()) {
            $parts[] = $this->hourlyRateFormatted().' руб.';
        }

        if ($this->isPaymentPerStudent()) {
            $parts[] = $this->perStudentAmountFormatted().' руб./слушатель';
        }

        return $parts !== [] ? implode(' + ', $parts) : '—';
    }

    public function perStudentAmountFormatted(): string
    {
        return $this->formatMoney((float) $this->per_student_amount);
    }

    public function perStudentAmountInWords(): string
    {
        return $this->amountInWords((float) $this->per_student_amount);
    }

    /**
     * @return array<int, string>
     */
    public static function workTypePresetLabels(): array
    {
        return [
            self::WORK_TYPE_LECTURES => 'Проведение лекционных и практических занятий по программе обучения{program_url_clause} в соответствии с утвержденным расписанием{schedule_url_clause}.',
            self::WORK_TYPE_NOTEBOOK => 'Проверка работ слушателей в рабочей тетради, оформленной по установленному шаблону{notebook_url_clause}.',
            self::WORK_TYPE_CONSULTING => 'Консультативная поддержка слушателей в процессе обучения (ответы на вопросы, возникающие при освоении материала, и разъяснение тем).',
            self::WORK_TYPE_ATTESTATION => 'Проведение итоговой аттестации слушателей по завершении программы обучения.',
            self::WORK_TYPE_CUSTOM => '{custom_text}',
        ];
    }

    /**
     * @param  array<int|string>  $codes
     */
    public static function buildWorkTypesText(
        array $codes,
        ?string $programUrl = null,
        ?string $scheduleUrl = null,
        ?string $notebookUrl = null,
        ?string $customText = null
    ): string {
        $presets = self::workTypePresetLabels();
        $lines = [];
        $index = 1;
        $customText = trim((string) $customText);

        foreach ($codes as $code) {
            $code = (int) $code;
            if (! isset($presets[$code])) {
                continue;
            }

            if ($code === self::WORK_TYPE_CUSTOM) {
                if ($customText === '') {
                    continue;
                }
                $line = $customText;
            } else {
                $line = str_replace(
                    ['{program_url_clause}', '{schedule_url_clause}', '{notebook_url_clause}'],
                    [
                        self::urlClause('ссылка на программу', $programUrl),
                        self::urlClause('ссылка на расписание', $scheduleUrl),
                        self::urlClause('ссылка на шаблон тетради', $notebookUrl),
                    ],
                    $presets[$code]
                );
            }

            $lines[] = $index.'. '.$line;
            $index++;
        }

        return implode("\n", $lines);
    }

    protected static function urlClause(string $label, ?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }

        return ' ('.$label.': '.$url.')';
    }

    public function hasWorkType(int $code): bool
    {
        return in_array($code, array_map('intval', $this->work_type_codes ?? []), true);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function studyGroup(): BelongsTo
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ContractReport::class, 'assignment_id')->orderBy('number');
    }

    public function report(): HasOne
    {
        return $this->hasOne(ContractReport::class, 'assignment_id')
            ->where('kind', ContractReport::KIND_MAIN);
    }

    public function perStudentReport(): HasOne
    {
        return $this->hasOne(ContractReport::class, 'assignment_id')
            ->where('kind', ContractReport::KIND_PER_STUDENT);
    }

    public function acts(): HasMany
    {
        return $this->hasMany(ContractAct::class, 'assignment_id')
            ->where('kind', ContractAct::KIND_MAIN)
            ->orderBy('number');
    }

    public function perStudentAct(): HasOne
    {
        return $this->hasOne(ContractAct::class, 'assignment_id')
            ->where('kind', ContractAct::KIND_PER_STUDENT);
    }

    public function issuedAtLabel(): string
    {
        return $this->dateLabel($this->issued_at);
    }

    public function startsAtLabel(): string
    {
        return $this->dateLabel($this->starts_at);
    }

    public function endsAtLabel(): string
    {
        return $this->dateLabel($this->ends_at);
    }

    public function hourlyRateFormatted(): string
    {
        return $this->formatMoney((float) $this->hourly_rate);
    }

    public function hourlyRateInWords(): string
    {
        return $this->amountInWords((float) $this->hourly_rate);
    }

    protected function formatMoney(float $amount): string
    {
        if (floor($amount) == $amount) {
            return (string) (int) $amount;
        }

        return number_format($amount, 2, ',', ' ');
    }

    protected function amountInWords(float $amount): string
    {
        $value = (int) round($amount);

        try {
            $words = CardinalNumeralGenerator::getCase($value, Cases::IMENIT);
        } catch (\Throwable) {
            return '—';
        }

        $words = trim((string) $words);

        return $words !== '' ? $words : '—';
    }

    public function studyGroupLabel(): string
    {
        $group = $this->studyGroup;
        if (! $group) {
            return '—';
        }

        $title = $group->title ?: ($group->short_title ?: '—');
        $dates = [];
        if ($group->starts_at) {
            $dates[] = $group->starts_at->format('d.m.Y');
        }
        if ($group->ends_at) {
            $dates[] = $group->ends_at->format('d.m.Y');
        }

        if ($dates === []) {
            return $title;
        }

        return $title.' ('.implode(' – ', $dates).')';
    }

    protected function dateLabel($date): string
    {
        if (! $date) {
            return '«___» __________ 20___ г.';
        }

        $formatted = $date->locale('ru')->isoFormat('DD MMMM YYYY');
        if (preg_match('/^(\d+)\s+(.+)$/u', $formatted, $matches)) {
            return '«'.$matches[1].'» '.$matches[2].' г.';
        }

        return $formatted.' г.';
    }

    protected function dateShortLabel($date): string
    {
        if (! $date) {
            return '«__» __________ 20__';
        }

        $formatted = $date->locale('ru')->isoFormat('DD MMMM YYYY');
        if (preg_match('/^(\d+)\s+(.+)$/u', $formatted, $matches)) {
            return '«'.$matches[1].'» '.$matches[2];
        }

        return $formatted;
    }

    public function startsAtShortLabel(): string
    {
        return $this->dateShortLabel($this->starts_at);
    }

    public function endsAtShortLabel(): string
    {
        return $this->dateShortLabel($this->ends_at);
    }

    public function statusLabel(): string
    {
        return ContractWorkflowStatus::label($this->status);
    }

    public function statusBadgeClass(): string
    {
        return ContractWorkflowStatus::badgeClass($this->status);
    }
}
