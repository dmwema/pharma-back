<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    public static function get_all($session_id)
    {
        $sch = Self::where('session_id', $session_id)->get();
        $return = [];
        $i = 0;
        foreach ($sch as $schedule) {
            $i++;
            $course = Course::find($schedule->course_id);
            $return[] = [
                'id' => $schedule->id,
                'key' => $i,
                'course' => $course->title . ' (' . Professor::find($course->current_prof_id)->lastname . ' ' . Professor::find($course->current_prof_id)->firstname . ')',
                'date' => $schedule->date,
            ];
        }
        return $return;
    }
}
