<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift_location extends Model
{
    use HasFactory;
    protected $table = 'work_shift_locations';

    protected $fillable = [
        'work_shift_id',
        'longitude',
        'latitude',
        'date_time'
    ];

    public function Work_shift(){
        return $this->belongsTo(Work_shift::class,'work_shift_id');
    }
}
