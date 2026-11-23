<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use morphos\Russian\CardinalNumeralGenerator;
use morphos\Russian\Cases;

class ContractAssignment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const WORK_TYPE_LECTURES = 1;
    public const WORK_TYPE_NOTEBOOK = 2;
    public const WORK_TYPE_CONSULTING = 3;
    public const WORK_TYPE_ATTESTATION = 4;
    public const WORK_TYPE_CUSTOM = 5;

    protected $table = 'contract_assignments';
    protected $guarded = false;

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'issued_at' => 'date',
        'work_type_codes' => 'array',
    ];

    /**
     * @return array<int, string>
     */
    public static function workTypePresetLabels(): array
    {
        return [
            self::WORK_TYPE_LECTURES => 'Проведение лекционных и практических занятий по программе обучения (ссылка на программу: {program_url}) в соответствии с утвержденным расписанием (ссылка на расписание: {schedule_url}).',
            self::WORK_TYPE_NOTEBOOK => 'Проверка работ слушателей в рабочей тетради, оформленной по установленному шаблону (ссылка на шаблон тетради: {notebook_url}).',
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
                    ['{program_url}', '{schedule_url}', '{notebook_url}'],
                    [
                        self::urlOrBlank($programUrl),
                        self::urlOrBlank($scheduleUrl),
                        self::urlOrBlank($notebookUrl),
                    ],
                    $presets[$code]
                );
            }

            $lines[] = $index.'. '.$line;
            $index++;
        }

        return implode("\n", $lines);
    }

    protected static function urlOrBlank(?string $url): string
    {
        $url = trim((string) $url);

        return $url !== '' ? $url : '____';
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
        $rate = (float) $this->hourly_rate;
        if (floor($rate) == $rate) {
            return (string) (int) $rate;
        }

        return number_format($rate, 2, ',', ' ');
    }

    public function hourlyRateInWords(): string
    {
        $amount = (int) round((float) $this->hourly_rate);

        try {
            $words = CardinalNumeralGenerator::getCase($amount, Cases::IMENIT);
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

        $formatted = $date->locale('ru')->isoFormat('D MMMM YYYY');
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

        $formatted = $date->locale('ru')->isoFormat('D MMMM YYYY');
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
}
