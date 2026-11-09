<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Org extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orgs';
    protected $guarded = false;

    protected $casts = [
        'is_published' => 'boolean',
        'politic' => 'boolean',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_org');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Опубликованные организации биоэкономики для выбора (уникальные по названию).
     *
     * @return Collection<int, self>
     */
    public static function publishedBioeconomyOptions(): Collection
    {
        return self::query()
            ->where('source', 'bioeconomy')
            ->where('is_published', true)
            ->whereNotNull('organization_title')
            ->where('organization_title', '!=', '')
            ->orderByDesc('id')
            ->get(['id', 'organization_title', 'inn'])
            ->unique(static function (self $org) {
                return mb_strtolower(trim($org->organization_title));
            })
            ->sortBy(static function (self $org) {
                return mb_strtolower($org->organization_title);
            }, SORT_NATURAL)
            ->values();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
