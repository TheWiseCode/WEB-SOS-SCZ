<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;
    protected $table = 'emergencies';
    protected $fillable = [
        'citizen_id',
        'type_institution_id',
        'description',
        'location'
    ];

    public function Citizen(){
        return $this->belongsTo(Cityzen::class,'citizen_id');
    }

    public function Type_institution(){
        return $this->belongsTo(type_institution::class,'type_institution_id');
    }
}
