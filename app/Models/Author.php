<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use morphos\Russian\Cases;

use function morphos\Russian\inflectName;

class Author extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'authors';
    protected $guarded = false;
    protected $casts = [
        'is_published' => 'boolean',
        'passport_issued_at' => 'date',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_authors', 'author_id', 'course_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->lastname,
            $this->firstname,
            $this->middlename,
        ])));
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name !== '' ? $this->full_name : (string) $this->name;
    }

    /**
     * ФИО в родительном падеже (для форм «от …»).
     */
    public function genitiveName(): string
    {
        $fullName = $this->full_name !== '' ? $this->full_name : (string) $this->name;
        if ($fullName === '') {
            return '—';
        }

        try {
            $inflected = inflectName($fullName, Cases::RODIT);
        } catch (\Throwable) {
            return $fullName;
        }

        $inflected = trim((string) $inflected);

        return $inflected !== '' ? $inflected : $fullName;
    }

    public function getSortNameAttribute(): string
    {
        $lastname = trim((string) $this->lastname);

        return trim(implode(' ', array_filter([
            $lastname !== '' ? $lastname : $this->name,
            $this->firstname,
            $this->middlename,
        ])));
    }
}
