<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contract_reports';
    protected $guarded = false;

    protected $casts = [
        'issued_at' => 'date',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'lesson_hours' => 'decimal:2',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ContractAssignment::class, 'assignment_id');
    }

    public function statusLabel(): string
    {
        return ContractWorkflowStatus::label($this->status);
    }

    public function statusBadgeClass(): string
    {
        return ContractWorkflowStatus::badgeClass($this->status);
    }

    public function issuedAtLabel(): string
    {
        return $this->dateLabel($this->issued_at);
    }

    public function startsAtShortLabel(): string
    {
        return $this->dateShortLabel($this->starts_at);
    }

    public function endsAtShortLabel(): string
    {
        return $this->dateShortLabel($this->ends_at);
    }

    public function lessonHoursFormatted(): string
    {
        if ($this->lesson_hours === null || $this->lesson_hours === '') {
            return '—';
        }

        $hours = (float) $this->lesson_hours;
        if (floor($hours) == $hours) {
            return (string) (int) $hours;
        }

        return number_format($hours, 2, ',', ' ');
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
}
