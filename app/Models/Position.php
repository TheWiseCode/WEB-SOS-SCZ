<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';
    protected $fillable = [
        'institution_id',
        'name',
        'description'
    ];

    public function Institutions(){
        return $this->belongsTo(institution::class,'institution_id');
    }

    public function Officer(){
        return $this->hasOne(Officer::class,'position_id');
    }
}
