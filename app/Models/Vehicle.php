<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';
    protected $fillable = [
        'type_vehicle_id',
        'name',
        'description'
    ];

    public function Type_vehicle(){
        return $this->belongsTo(Type_vehicle::class,'type_vehicle_id');
    }
    public function Work_shift(){
        return $this->hasMany(Work_shift::class,'vehicle_id');
    }
}
