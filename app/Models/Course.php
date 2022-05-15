<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Professor;
use App\Models\Examen;
use App\Models\AnnualWork;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'ponderation'
    ];

    public function professors(){
        return $this->belongsToMany(Professor::class);
    }

    public function examens(){
        return $this->hasMany(Examen::class);
    }

    public function annual_works(){
        return $this->hasMany(AnnualWork::class);
    }

    public function promotions(){
        return $this->belongsToMany(Promotion::class);
    }
}
