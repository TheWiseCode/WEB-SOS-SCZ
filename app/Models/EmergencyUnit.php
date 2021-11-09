<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyUnit extends Model
{
    use HasFactory;

    protected $table = 'emergency_units';
    protected $fillable = [
        'type',
        'vehicle_license',
        'description',
        'helper_id'
    ];

    public function helper()
    {
        return $this->hasOne(Helper::class);
    }
}
