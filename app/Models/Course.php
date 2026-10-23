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

    public const TYPES = [
        'advanced_training' => 'Повышение квалификации',
        'professional_retraining' => 'Профессиональная переподготовка',
        'professional_education' => 'Профессиональное обучение',
    ];

    protected $table = 'courses';
    protected $guarded = false;

    protected $casts = [
        'starts_at' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function (Course $course) {
            $course->short_title = self::makeShortTitle((string) $course->title, $course->years);
        });
    }

    /**
     * Предлоги, которые не участвуют в кратком названии.
     */
    public const SHORT_TITLE_SKIP_WORDS = [
        'без', 'в', 'во', 'для', 'до', 'за', 'из', 'к', 'ко', 'между', 'на', 'над',
        'о', 'об', 'обо', 'около', 'от', 'перед', 'по', 'под', 'подо', 'при', 'про',
        'против', 'с', 'со', 'среди', 'у', 'через',
    ];

    /**
     * Краткое название: первые буквы русских слов (по сегментам через точку) + последние 2 цифры года.
     * Предлоги, латиница и текст в скобках не учитываются.
     * Пример: «Азбука цифры. Нейросети в SMM-маркетинге» + 2027 → «АЦ-НМ-27».
     */
    public static function makeShortTitle(string $title, $years = null): string
    {
        // Текст в скобках не участвует в кратком названии.
        $title = preg_replace('/\([^()]*\)/u', ' ', $title) ?? $title;
        $title = preg_replace('/\s+/u', ' ', $title) ?? $title;

        $parts = preg_split('/\./u', $title) ?: [];
        $abbrParts = [];
        $skipWords = array_fill_keys(self::SHORT_TITLE_SKIP_WORDS, true);
        $wordsUsed = 0;
        $maxWords = 6;

        foreach ($parts as $part) {
            if ($wordsUsed >= $maxWords) {
                break;
            }

            $part = trim($part);
            if ($part === '') {
                continue;
            }

            $words = preg_split('/\s+/u', $part, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $letters = '';

            foreach ($words as $word) {
                if ($wordsUsed >= $maxWords) {
                    break;
                }

                $word = preg_replace('/^[^\p{L}\p{N}]+|[^\p{L}\p{N}]+$/u', '', $word) ?? '';
                if ($word === '') {
                    continue;
                }

                if (! preg_match('/\p{Cyrillic}/u', $word)) {
                    continue;
                }

                $normalized = mb_strtolower($word, 'UTF-8');
                if (isset($skipWords[$normalized])) {
                    continue;
                }

                if (! preg_match('/\p{Cyrillic}/u', $word, $cyrillicMatch)) {
                    continue;
                }

                $letters .= mb_strtoupper($cyrillicMatch[0], 'UTF-8');
                $wordsUsed++;
            }

            if ($letters !== '') {
                $abbrParts[] = $letters;
            }
        }

        $yearDigits = preg_replace('/\D+/', '', (string) $years) ?? '';
        $yearSuffix = $yearDigits !== '' ? substr($yearDigits, -2) : '';

        if ($yearSuffix !== '') {
            $abbrParts[] = $yearSuffix;
        }

        return implode('-', $abbrParts);
    }

    public function authors(){
        return $this->belongsToMany(Author::class, 'course_authors', 'course_id', 'author_id');
    }
    public function links(){
        return $this->hasMany(Link::class );
    }

    public function studyGroups()
    {
        return $this->hasMany(StudyGroup::class);
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

    public function typeLabel(): ?string
    {
        if (! is_string($this->type)) {
            return null;
        }

        return self::TYPES[$this->type] ?? null;
    }

    public function programTypePhrase(): ?string
    {
        return match ($this->type) {
            'professional_retraining' => 'дополнительной профессиональной программы профессиональной переподготовки',
            'advanced_training' => 'дополнительной программы повышения квалификации',
            'professional_education' => 'программы профессионального обучения',
            default => null,
        };
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

    public function studyHoursLabel(): ?string
    {
        if ($this->study_hours === null || $this->study_hours === '') {
            return null;
        }

        return ((int) $this->study_hours).' ак.ч.';
    }

    public function studyHoursFullLabel(): ?string
    {
        if ($this->study_hours === null || $this->study_hours === '') {
            return null;
        }

        $hours = (int) $this->study_hours;
        $mod100 = $hours % 100;
        $mod10 = $hours % 10;

        if ($mod100 >= 11 && $mod100 <= 14) {
            $word = 'часов';
        } elseif ($mod10 === 1) {
            $word = 'час';
        } elseif ($mod10 >= 2 && $mod10 <= 4) {
            $word = 'часа';
        } else {
            $word = 'часов';
        }

        return $hours.' '.$word;
    }
}
