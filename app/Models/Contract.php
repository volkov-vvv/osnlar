<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'contracts';
    protected $guarded = false;

    protected $casts = [
        'signed_at' => 'date',
        'nap_registered_at' => 'date',
        'ends_at' => 'date',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function signedAtLabel(): string
    {
        if (! $this->signed_at) {
            return '«___» __________ 20___ г.';
        }

        // isoFormat with day uses genitive month names in ru (октября, не октябрь)
        $formatted = $this->signed_at->locale('ru')->isoFormat('D MMMM YYYY');
        if (preg_match('/^(\d+)\s+(.+)$/u', $formatted, $matches)) {
            return '«'.$matches[1].'» '.$matches[2].' г.';
        }

        return $formatted.' г.';
    }

    public function napRegisteredAtLabel(): string
    {
        if (! $this->nap_registered_at) {
            return '00.00.0000 г.';
        }

        return $this->nap_registered_at->format('d.m.Y').' г.';
    }

    public function endsAtLabel(): string
    {
        if (! $this->ends_at) {
            return '«___» __________ 20___ г.';
        }

        $formatted = $this->ends_at->locale('ru')->isoFormat('D MMMM YYYY');
        if (preg_match('/^(\d+)\s+(.+)$/u', $formatted, $matches)) {
            return '«'.$matches[1].'» '.$matches[2].' г.';
        }

        return $formatted.' г.';
    }

    public function assignNumber(): void
    {
        $year = optional($this->created_at)->year ?? (int) date('Y');

        $this->forceFill([
            'number' => $this->id.'/'.$year.'ДПУ',
        ])->save();
    }

    public function getTemplateTitleAttribute(): string
    {
        return (string) (config('contracts.templates.'.$this->template.'.title') ?? $this->template);
    }

    public function getTemplateViewAttribute(): ?string
    {
        return config('contracts.templates.'.$this->template.'.view');
    }
}
