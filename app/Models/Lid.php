<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Lid extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'lids';
    protected $guarded = false;

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

}
