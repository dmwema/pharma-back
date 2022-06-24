<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Professor;
use App\Models\Examen;
use App\Models\AnnualWork;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use phpDocumentor\Reflection\Types\Boolean;

class Course extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'ponderation'
    ];

    public function professors()
    {
        return $this->belongsToMany(Professor::class);
    }

    public function examens()
    {
        return $this->hasMany(Examen::class);
    }

    public function annual_works()
    {
        return $this->hasMany(AnnualWork::class, 'course_id', 'id');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class);
    }

    public static function with_annual_works($teacher_id)
    {
        $courses = Course::where('current_prof_id', $teacher_id)->get();
        $courses_ret = [];
        foreach ($courses as $course) {
            $annual_w = AnnualWork::where('course_id', $course->id)->where('session_id', null)->get();

            $courses_ret[] = [
                'id' => $course->id,
                'title' => $course->title,
                'created_at' => $course->created_at,
                'current_prof_id' => $course->current_prof_id,
                'current_promotion_id' => $course->current_promotion_id,
                'annual_works' => $annual_w
            ];
        }

        return $courses_ret;
    }

    public static function with_annual_works_with($teacher_id)
    {
        $courses = Course::where('current_prof_id', $teacher_id)->get();
        $courses_ret = [];
        foreach ($courses as $course) {
            $annual_w = AnnualWork::where('course_id', $course->id)->get();
            $annual_w_ret = [];

            foreach ($annual_w as $w) {
                $annual_w_ret[] = [
                    'students' => Student::with_cotes($w->id),
                    'id' => $w->id,
                    'title' => $w->title,
                    'description' => $w->description,
                    'date_work' => $w->date_work,
                    'description' => $w->description,
                    'course_id' => $w->course_id,
                    'max' => $w->max,
                ];
            }

            $courses_ret[] = [
                'id' => $course->id,
                'title' => $course->title,
                'created_at' => $course->created_at,
                'current_prof_id' => $course->current_prof_id,
                'current_promotion_id' => $course->current_promotion_id,
                'annual_works' => $annual_w_ret
            ];
        }

        return $courses_ret;
    }
}
