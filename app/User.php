<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password','name','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function facebook()
    {
        return $this->hasOne('App\SocialFacebookAccount','user_id');
    }

    public function teacher()
    {
        return $this->hasOne('App\Teacher', 'user_id');
    }

    public function student()
    {
        return $this->hasOne('App\Student', 'user_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Parents', 'user_id');
    }

    public function inquiry()
    {
        return $this->hasMany('App\Inquiry', 'assigned_staff', 'id');
    }
}
