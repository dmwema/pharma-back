<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;

class DeliberationCourse extends Model
{
    public static function get_all($deliberation_id)
    {
        $deliberationCourses = DeliberationCourse::where('deliberation_id', $deliberation_id)->get();
        $return = [];
        $i = 0;
        foreach ($deliberationCourses as $cotes) {
            $i++;
            $course = Course::find($cotes->course_id);
            //return $course;
            $professor = Professor::find($course->current_prof_id);
            $return[] = [
                'key' => $cotes->id,
                'id' => $i,
                'course' => $course->title . ' (' . $professor->lastname . ' ' . $professor->middlename . ' ' . $professor->firstname . ')',
                'has_sent' => $cotes->has_sent ? 'OUI' : 'NON',
                'date' => $cotes->created_at,
                'deliberation_id' => $cotes->deliberation_id,
            ];
        }

        return $return;
    }
}
