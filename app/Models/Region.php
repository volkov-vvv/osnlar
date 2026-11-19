<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Region extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'regions';
    protected $guarded = false;

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Top AMSZ regions by lid count for a course year.
     */
    public static function topByAmszLidsForYear($year, int $limit = 10): Collection
    {
        return static::query()
            ->select(['regions.id', 'regions.title'])
            ->selectRaw('COUNT(lids.id) as lids_count')
            ->join('lids', 'lids.region_id', '=', 'regions.id')
            ->join('courses', 'courses.id', '=', 'lids.course_id')
            ->where('courses.years', (string) $year)
            ->whereNull('courses.price')
            ->where(function ($query) {
                $query->where('courses.bioeconomy', '!=', 1)
                    ->orWhereNull('courses.bioeconomy');
            })
            ->whereNull('lids.deleted_at')
            ->whereNull('courses.deleted_at')
            ->groupBy('regions.id', 'regions.title')
            ->orderByDesc('lids_count')
            ->orderBy('regions.title')
            ->limit($limit)
            ->get();
    }
}
