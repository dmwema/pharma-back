<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Promotion;
use App\Models\Examen;
use App\Models\AnnualWork;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'gender',
        'avatar',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function promotions(){
        return $this->belongsToMany(Promotion::class)->withPivot(['year_start','year_off']);
    }

    public function examens(){
        return $this->belongsToMany(Examen::class)->withPivot('cote');
    }

    public function annual_works(){
        return $this->belongsToMany(AnnualWork::class)->withPivot('cote');
    }
}
