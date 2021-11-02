<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;

    protected $fillable = [
        'type','rank','emergency_unit', 'in_turn', 'user_id'
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function workdays(){
        return $this->hasMany(Workday::class);
    }
}
