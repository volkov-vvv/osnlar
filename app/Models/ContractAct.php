<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use morphos\Russian\CardinalNumeralGenerator;
use morphos\Russian\Cases;

class ContractAct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contract_acts';
    protected $guarded = false;

    protected $casts = [
        'issued_at' => 'date',
        'amount' => 'decimal:2',
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
        if (! $this->issued_at) {
            return '«___» __________ 20___ г.';
        }

        $formatted = $this->issued_at->locale('ru')->isoFormat('DD MMMM YYYY');
        if (preg_match('/^(\d+)\s+(.+)$/u', $formatted, $matches)) {
            return '«'.$matches[1].'» '.$matches[2].' г.';
        }

        return $formatted.' г.';
    }

    public function amountFormatted(): string
    {
        if ($this->amount === null || $this->amount === '') {
            return '—';
        }

        $amount = (float) $this->amount;
        if (floor($amount) == $amount) {
            return (string) (int) $amount;
        }

        return number_format($amount, 2, ',', ' ');
    }

    public function amountInWords(): string
    {
        if ($this->amount === null || $this->amount === '') {
            return '—';
        }

        $amount = (int) round((float) $this->amount);

        try {
            $words = CardinalNumeralGenerator::getCase($amount, Cases::IMENIT);
        } catch (\Throwable) {
            return '—';
        }

        $words = trim((string) $words);

        return $words !== '' ? $words : '—';
    }

    /**
     * Сумма по умолчанию: ставка задания × часы отчёта.
     */
    public static function suggestedAmountForAssignment(ContractAssignment $assignment): ?float
    {
        $assignment->loadMissing('report');
        $hours = $assignment->report?->lesson_hours;
        if ($hours === null || $hours === '') {
            return null;
        }

        return round((float) $assignment->hourly_rate * (float) $hours, 2);
    }
}
