<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Session;
use App\Models\Course;

class Examen extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description',
        'date_examen',
        'course_id'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function sessions(){
        return $this->belongsToMany(Session::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class)->withPivot('cote');
    }
}
