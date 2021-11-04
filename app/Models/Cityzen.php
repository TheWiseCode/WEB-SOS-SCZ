<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cityzen extends Model
{
    use HasFactory;

    protected $table = 'cityzens';
    protected $fillable = [
        'user_id'
    ];
    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function Emergency(){
        return $this->hasMany(Emergency::class,'citizen_id');
    }
}
