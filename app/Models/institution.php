<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class institution extends Model
{
    use HasFactory;

    protected $table = 'institutions';
    protected $fillable = [
        'name',
        'description',
        'type_institution_id',
        'address',
        'location'
    ];

    public function Type_institution(){
        return $this->belongsTo(type_institution::class,'type_institution_id');
    }

    public function Position(){
        return $this->hasMany(Position::class,'institution_id');
    }

    public function Officer(){
        return $this->hasMany(Officer::class,'institution_id');
    }
}
