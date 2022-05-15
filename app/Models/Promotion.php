<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Department;
use App\Models\Student;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_promotion',
        'department_id'
    ];

    public function students(){
        return $this->belongsToMany(Student::class)->withPivot(['year_start','year_off']);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
}
