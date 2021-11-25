<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_turn', 'start_turn', 'end_turn', 'helper_id'
    ];

    public function helper(){
        return $this->belongsTo(Helper::class);
    }
}
