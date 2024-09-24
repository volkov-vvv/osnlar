<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ROLE_ADMIN = 1;
    const ROLE_AGENT = 2;
    const ROLE_CONTACT = 3;

    public function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_AGENT => 'Агент',
            self::ROLE_CONTACT => 'Специалист КЦ',
        ];
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function getLids()
    {
        return $this->hasMany(Lid::class, 'responsible_id', 'id');
    }
/*
    public function averageTime()
    {
        $queryArray = array(
            'lids.created_at',
            'activity_log.created_at',
        );
        $query = Lid::select($queryArray)
            ->join('activity_log', 'activity_log.subject_id', '=', 'lids.id')
            ->join('users', 'activity_log.causer_id', '=', 'users.id')
            ->where('lids.id', mb_substr($searchValue, 1));
        $activities = $query->get();
    }
*/
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'utm'
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
