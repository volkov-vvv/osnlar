<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'authors';
    protected $guarded = false;

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
