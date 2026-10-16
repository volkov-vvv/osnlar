<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STUDY_FORMS = [
        'full_time' => 'Очная',
        'blended' => 'Очно-заочная',
        'online' => 'Онлайн',
    ];

    protected $table = 'courses';
    protected $guarded = false;

    protected $casts = [
        'starts_at' => 'date',
    ];

    public function authors(){
        return $this->belongsToMany(Author::class, 'course_authors', 'course_id', 'author_id');
    }
    public function links(){
        return $this->hasMany(Link::class );
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function defaultStatus()
    {
        return $this->belongsTo(Status::class, 'default_status_id');
    }

    public function isPaid(): bool
    {
        return $this->price !== null && $this->price !== '';
    }

    public function studyFormLabel(): ?string
    {
        if (!is_string($this->study_form)) {
            return null;
        }

        return self::STUDY_FORMS[$this->study_form] ?? null;
    }

    public function studyWeeksLabel(): ?string
    {
        if ($this->study_weeks === null || $this->study_weeks === '') {
            return null;
        }

        $weeks = (int) $this->study_weeks;
        $mod100 = $weeks % 100;
        $mod10 = $weeks % 10;

        if ($mod100 >= 11 && $mod100 <= 14) {
            $word = 'недель';
        } elseif ($mod10 === 1) {
            $word = 'неделя';
        } elseif ($mod10 >= 2 && $mod10 <= 4) {
            $word = 'недели';
        } else {
            $word = 'недель';
        }

        return $weeks.' '.$word;
    }
}
