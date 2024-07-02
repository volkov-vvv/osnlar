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

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

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

    public function scopeFilter($query, $params)
    {
        $query = Lid::select('lids.*')
            ->join('courses', 'courses.id', '=', 'lids.course_id')
            ->join('regions', 'regions.id', '=', 'lids.region_id')
            ->join('statuses', 'statuses.id', '=', 'lids.status_id')
            ->leftjoin('users', 'users.id', '=', 'lids.responsible_id');

        // Поиск
        if ( isset($params['search']) ) {
            $searchValue = $params['search'];
            $query->where(function ($query) use ($searchValue) {
                $query->where('courses.title', 'like', '%' . $searchValue . '%')
                    ->orwhere('regions.title', 'like', '%' . $searchValue . '%')
                    ->orwhere('lids.firstname', 'like', '%' . $searchValue . '%')
                    ->orwhere('lids.lastname', 'like', '%' . $searchValue . '%')
                    ->orwhere('lids.email', 'like', '%' . $searchValue . '%')
                    ->orwhere('lids.phone', 'like', '%' . $searchValue . '%');
            });
        }

        // Фильтры
        if ( isset($params['responsible']) ) {
            $query->where('users.name', 'like', $params['responsible'] );
        }

        if ( isset($params['course']) ) {
            $query->where('courses.title', 'like', $params['course'] );
        }

        if ( isset($params['region']) ) {
            $query->where('regions.title', 'like', $params['region'] );
        }

        if ( isset($params['status']) ) {
            $query->where('statuses.title', 'like', $params['status'] );
        }

        return $query;
    }

}
