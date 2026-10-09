<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Org extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orgs';
    protected $guarded = false;

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_org');
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
