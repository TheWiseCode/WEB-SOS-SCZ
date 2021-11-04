<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_vehicle extends Model
{
    use HasFactory;
    protected $table = 'type_vehicles';
    protected $fillable = [
        'name',
        'characteristics'
    ];

    public function Vehicle(){
        return $this->hasMany(Vehicle::class,'type_vehicle_id');
    }

}
