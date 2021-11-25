<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'home_address',
        'cellphone',
        'birthday',
        'sex',
        'email',
        'password',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notificationDevices()
    {
        return $this->hasMany(NotificationDevice::class);
    }

    public function civilian()
    {
        return $this->belongsTo(Civilian::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function helper()
    {
        return $this->belongsTo(Helper::class);
    }

}
