<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;


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

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'id', 'subject_id')->where('description', '=', 'Изменение статуса');
    }

}
