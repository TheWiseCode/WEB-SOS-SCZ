<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'rank', 'in_turn', 'longitude', 'latitude', 'user_id'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function emergency(){
        return $this->hasMany(Emergency::class);
    }

    public function locationHistory(){
        return $this->hasMany(LocationHistory::class);
    }
    /*
    public function workShifts()
    {
        return $this->hasMany(WorkShift::class);
    }

    public function emergencyUnit()
    {
        return $this->belongsTo(EmergencyUnit::class);
    }
    */
}
