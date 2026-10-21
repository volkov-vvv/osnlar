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
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
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
}
