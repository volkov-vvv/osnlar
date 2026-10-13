<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Year extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'years';

    protected $guarded = false;

    protected $casts = [
        'value' => 'integer',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sort' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderByDesc('value');
    }

    /**
     * Active years as a list of year values for selects.
     */
    public static function options(): Collection
    {
        return static::query()
            ->active()
            ->ordered()
            ->pluck('value');
    }

    /**
     * Default year value for filters and dashboards.
     */
    public static function defaultValue(): int
    {
        $default = static::query()
            ->active()
            ->where('is_default', true)
            ->value('value');

        if ($default) {
            return (int) $default;
        }

        $fallback = static::query()
            ->active()
            ->ordered()
            ->value('value');

        return $fallback ? (int) $fallback : (int) date('Y');
    }

    /**
     * Ensure only one default year exists.
     */
    public function makeDefault(): void
    {
        static::query()
            ->where('id', '!=', $this->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $this->forceFill([
            'is_default' => true,
            'is_active' => true,
        ])->save();
    }
}
