<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'study_groups';
    protected $guarded = false;

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function durationLabel(): ?string
    {
        $totalDays = $this->totalDays();
        if ($totalDays === null) {
            return null;
        }

        $weeks = intdiv($totalDays, 7);
        $days = $totalDays % 7;
        $parts = [];

        if ($weeks > 0) {
            $parts[] = $weeks.' '.$this->pluralize($weeks, 'неделя', 'недели', 'недель');
        }
        if ($days > 0 || $weeks === 0) {
            $parts[] = $days.' '.$this->pluralize($days, 'день', 'дня', 'дней');
        }

        return implode(' ', $parts);
    }

    public function workdaysCount(): ?int
    {
        if (! $this->starts_at instanceof CarbonInterface || ! $this->ends_at instanceof CarbonInterface) {
            return null;
        }
        if ($this->ends_at->lt($this->starts_at)) {
            return null;
        }

        $workdays = 0;
        $current = $this->starts_at->copy()->startOfDay();
        $end = $this->ends_at->copy()->startOfDay();

        while ($current->lte($end)) {
            if (! $current->isWeekend()) {
                $workdays++;
            }
            $current->addDay();
        }

        return $workdays;
    }

    public function workdaysLabel(): ?string
    {
        $workdays = $this->workdaysCount();
        if ($workdays === null) {
            return null;
        }

        return $workdays.' '.$this->pluralize($workdays, 'день', 'дня', 'дней');
    }

    protected function totalDays(): ?int
    {
        if (! $this->starts_at instanceof CarbonInterface || ! $this->ends_at instanceof CarbonInterface) {
            return null;
        }
        if ($this->ends_at->lt($this->starts_at)) {
            return null;
        }

        return $this->starts_at->diffInDays($this->ends_at) + 1;
    }

    protected function pluralize(int $value, string $one, string $few, string $many): string
    {
        $n = abs($value) % 100;
        $n1 = $n % 10;

        if ($n > 10 && $n < 20) {
            return $many;
        }
        if ($n1 > 1 && $n1 < 5) {
            return $few;
        }
        if ($n1 === 1) {
            return $one;
        }

        return $many;
    }
}