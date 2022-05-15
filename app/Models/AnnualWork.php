<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnnualWork extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'date_work',
        'course_id'
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class)->withPivot('cote');
    }
}
