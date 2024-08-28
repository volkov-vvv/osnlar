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

    public function category()
    {
        return $this->belongsTo(Category::class);
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

    public function activities()
    {
        return $this->hasMany(Activity::class, 'subject_id', 'id');
    }

    public function scopeFilter($query, $params)
    {
        $query = Lid::select(array('lids.*', 'statuses.title as status', 'statuses.color as status_color'))
            ->join('courses', 'courses.id', '=', 'lids.course_id')
            ->join('regions', 'regions.id', '=', 'lids.region_id')
            ->join('statuses', 'statuses.id', '=', 'lids.status_id')
            ->leftjoin('categories', 'categories.id', '=', 'lids.category_id')
            ->leftjoin('agents', 'agents.id', '=', 'lids.agent_id')
            ->leftjoin('users', 'users.id', '=', 'lids.responsible_id');

        // Поиск
        if ( isset($params['search']) ) {
            $searchValue = $params['search'];
            $firstChar = mb_substr($searchValue,0,1);

            if(($firstChar == '#') || ($firstChar == '№')){
                $query->where('lids.id', mb_substr($searchValue, 1));
            }else{
                $query->where(function ($query) use ($searchValue) {
                    $query->where('courses.title', 'like', '%' . $searchValue . '%')
                        ->orwhere('regions.title', 'like', '%' . $searchValue . '%')
                        ->orwhere('lids.id', $searchValue)
                        ->orwhere('lids.firstname', 'like', '%' . $searchValue . '%')
                        ->orwhere('lids.lastname', 'like', '%' . $searchValue . '%')
                        ->orwhere('lids.email', 'like', '%' . $searchValue . '%')
                        ->orwhere('lids.phone', 'like', '%' . $searchValue . '%')
                        ->orwhere('lids.utm_source', 'like', '%' . $searchValue . '%')
                        ->orwhere('lids.utm_medium', 'like', '%' . $searchValue . '%')
                        ->orwhere('lids.utm_campaign', 'like', '%' . $searchValue . '%');
                });
            }

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

        if ( isset($params['agent']) ) {
            $query->where('agents.title', 'like', $params['agent'] );
        }

        if ( isset($params['utm_source']) ) {
            $query->where('lids.utm_source', 'like', $params['utm_source'] );
        }

        if ( isset($params['utm_medium']) ) {
            $query->where('lids.utm_medium', 'like', $params['utm_medium'] );
        }

        if ( isset($params['utm_campaign']) ) {
            $query->where('lids.utm_campaign', 'like', $params['utm_campaign'] );
        }

        if ( isset($params['created_at']) ) {
            $query->where('lids.created_at', 'like', '%' . $params['created_at'] . '%' );
        }
//dd($query->toSql());
        return $query;
    }

}
