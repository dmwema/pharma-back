<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Promotion;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function promotions(){
        return $this->hasMany(Promotion::class);
    }
}
