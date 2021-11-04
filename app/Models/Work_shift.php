<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_shift extends Model
{
    use HasFactory;
    protected $table = 'work_shifts';
    protected $fillable = [
        'officer_id',
        'vehicle_id',
        'schedule_id',
        'shift_starts',
        'shift_ends'
    ];

    public function Vehicle(){
        return $this->belongsTo(Vehicle::class,'vehicle_id');
    }

    public function Officer(){
        return $this->belongsTo(Officer::class,'officer_id');
    }

    public function Schedule(){
        return $this->belongsTo(Schedule::class,'schedule_id');
    }

    public function WorkShift_location(){
        return $this->hasMany(WorkShift_location::class,'work_shift_id');
    }

}
