<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;
    protected $table = 'officers';
    protected $fillable = [
      'user_id',
      'institution_id',
      'position_id'
    ];

    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function Position(){
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function Institution(){
        return $this->belongsTo(institution::class,'institution_id');
    }

    public function Work_shift(){
        return $this->hasMany(Work_shift::class,'officer_id');
    }


}
