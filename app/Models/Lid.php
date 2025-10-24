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

    public function company()
    {
        return $this->belongsTo(Company::class);
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

    public function getLidsCount($courseYear){
        $lidsCount = Lid::select('lids.id')
            ->leftjoin('courses', 'lids.course_id', '=', 'courses.id')
            ->where('courses.years', $courseYear)
            ->whereNull('lids.deleted_at')->count();
        return $lidsCount;
    }

    public function activities()
    {
        $queryArray = array(
            'activity_log.causer_id',
            'activity_log.properties',
            'activity_log.description',
            'activity_log.subject_id',
            'activity_log.created_at',
            'users.name as user_name',
        );
        $query = Lid::select($queryArray)
            ->join('activity_log', 'activity_log.subject_id', '=', 'lids.id')
            ->join('users', 'activity_log.causer_id', '=', 'users.id');
        return $query->get();
    }

    public function activities2()
    {
        return $this->hasMany(Activity::class, 'subject_id', 'id');
    }

    public function scopeFilter($query, $params)
    {
        $queryArray = array(
            'lids.*',
            'statuses.title as status_title', 'statuses.color as status_color',
            'courses.title as course_title',
            'courses.years as course_years',
            'regions.title as region_title',
            'agents.title as agent_title',
            'users.name as responsible_name',
            'companies.title as company_title',
        );
        $query = Lid::select($queryArray)
            ->join('courses', 'courses.id', '=', 'lids.course_id')
            ->join('regions', 'regions.id', '=', 'lids.region_id')
            ->join('statuses', 'statuses.id', '=', 'lids.status_id')
            ->leftjoin('companies', 'companies.id', '=', 'lids.company_id')
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


 //       $query->whereNull('courses.price');


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

        if ( isset($params['company']) ) {
            if($params['company'] == 'Основание'){
                $query->where(function ($query) {
                    $query->where('lids.company_id', '=', 1)
                        ->orWhereNull('lids.company_id');
                });
            }else {
                $query->where('companies.title', 'like', '%' . $params['company'] . '%');
            }
        }


        // Фильтр по компании для сотрудников КЦ
        $user = auth()->user();
        if($user->role == 3 && !empty($user->company_id)){
            $query->where('companies.id', $user->company_id);
        }

        if ( isset($params['year']) ) {
            $query->where('courses.years', $params['year']);
        }else{
            $query->where('courses.years', '2026');
        }

        return $query;
    }

    //Коммерческие заявки
    public function commercial()
    {
        return $this->belongsTo(Course::class)->whereNotNull('price');
    }

}
