<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'links';
    protected $guarded = false;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
