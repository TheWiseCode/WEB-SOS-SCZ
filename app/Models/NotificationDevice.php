<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationDevice extends Model
{
    use HasFactory;

    protected $fillable = [
      'name_device', 'token', 'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
