<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergency extends Model
{
    use HasFactory;

    protected $table = 'emergencies';
    protected $fillable = [
        'type',
        'state',
        'description',
        'longitude',
        'latitude',
        'civilian_id',
    ];

    public function civilian()
    {
        return $this->belongsTo(Civilian::class);
    }
}
