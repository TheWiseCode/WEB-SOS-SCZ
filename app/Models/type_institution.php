<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type_institution extends Model
{
    use HasFactory;
    protected $table = 'type_institutions';
    protected $fillable = [
        'name',
        'description'
    ];

    public function Emergency(){
        return $this->hasMany(Emergency::class,'type_institution_id');
    }

    public function Institution(){
        return $this->hasMany(institution::class,'type_institution_id');
    }
}
