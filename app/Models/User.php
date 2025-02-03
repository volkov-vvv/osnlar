<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ROLE_ADMIN = 1;
    const ROLE_AGENT = 2;
    const ROLE_CONTACT = 3;
    const ROLE_USER = 10;


    public function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_AGENT => 'Агент',
            self::ROLE_CONTACT => 'Специалист КЦ',
            self::ROLE_USER => 'Пользователь',
        ];
    }

    public function agents()
    {
        return $this->belongsToMany(Agent::class)->withPivot('user_id','agent_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

        public function getLids()
    {
        return $this->hasMany(Lid::class, 'responsible_id', 'id');
    }

    public function  getActiveLids()
    {
        $queryArray = array(
            'activity_log.causer_id',
            'activity_log.properties',
            'activity_log.description',
            'activity_log.subject_id',
            'activity_log.created_at',
        );
        $query = Activity::select($queryArray)
            ->leftjoin('lids', 'activity_log.subject_id', '=', 'lids.id')
            ->where('lids.responsible_id', $this->id)
            ->whereNull('lids.deleted_at');

        $activeLidsCount = $query->get()->unique('subject_id')->count();
        $lidsCount = Lid::whereNull('lids.deleted_at')->count();

        $ActiveLidsPersent = round($activeLidsCount / $lidsCount * 100, 2);

        $ActiveLids = collect(['count' => $activeLidsCount, 'persent' => $ActiveLidsPersent]);

        return $ActiveLids;
    }

    public function averageTime()
    {
        $queryArray = array(
            'lids.created_at AS lid_create',
            'activity_log.created_at As activity_create',
            'activity_log.subject_id'
        );
        $query = Activity::select($queryArray)
            ->leftjoin('lids', 'activity_log.subject_id', '=', 'lids.id')
            ->where('lids.responsible_id', $this->id)
            ->where('activity_log.description', 'Изменение статуса')
            ->whereNull('lids.deleted_at')
            ->orderBy('activity_log.created_at');
        $activities = $query->get()->unique('subject_id');

            $diffTime = 0;
            foreach ($activities as $activity){
                $diffTime += strtotime($activity->activity_create) - strtotime($activity->lid_create);
            }
            if($activities->count() != 0){
                $diff = round($diffTime / $activities->count(), 0);
                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(
                    ($diff - $years * 365*60*60*24 -
                        $months * 30*60*60*24) / (60*60*24));
                $hours = floor(
                    ($diff - $years * 365*60*60*24 - $months *
                        30*60*60*24 - $days * 60*60*24) / (60*60));
                $minutes = floor(
                    ($diff - $years * 365*60*60*24 - $months * 30*60*60*24 -
                        $days * 60*60*24 - $hours * 60*60) / 60);
                $seconds = floor(($diff - $years * 365*60*60*24 - $months *
                    30*60*60*24 - $days * 60*60*24 - $hours *
                    60*60 - $minutes * 60));
                $medianTime = $minutes . ' мин.';
                if($hours > 0) $medianTime = $hours . 'ч. ' . $medianTime;
                if($days > 0) $medianTime = $days . 'д. ' . $medianTime;
            }else{
                $medianTime = '---';
            }
            return $medianTime;

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'middlename',
        'lastname',
        'email',
        'phone_prefix',
        'phone',
        'password',
        'role',
        'utm',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'utm' => 'array',
    ];
}
