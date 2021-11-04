<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';
    protected $fillable = [
        'name',
        'description',
        'start',
        'end'
    ];

    public function Work_shift(){
        return $this->hasMany(Work_shift::class,'schedule_id');
    }
}
