<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    use HasFactory;

    protected $table = 'location_history';
    protected $fillable = [
        'helper_id', 'longitude', 'latitude', 'date', 'time'
    ];

    public function helper(){
        return $this->belongsTo(Helper::class);
    }
}
