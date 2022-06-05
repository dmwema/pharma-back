<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Deliberation;
use App\Models\Student;
use App\Models\StudentDeliberation;
use App\Models\StudentWork;
use Illuminate\Http\Request;

class StudentDeliberationController extends Controller
{
    public function sendCotes(Request $request)
    {
        $cotes = $request->cotes;
        $annual = $cotes->annual;
        $exam = $cotes->exam;
        $promotion_id = $request->promotion_id;
        $course_id = $request->course_id;
        $works = AnnualWork::where('course_id', $course_id);

        $students = Student::where('current_promotion_id', $promotion_id)->get();

        foreach ($students as $student) {
            $annual_st = null;
            $exam_st = null;
            $annual_arr = [];
            $exam_arr = [];

            foreach ($works as $work) {
                $student_cote = StudentWork::where('student_id', $student->id)->where('work_id', $work->id)->first();
                if ($annual[$work->id]) {
                    $work = AnnualWork::find($work->id);
                    $annual_arr[$work->max] = $student_cote->cote;
                }
                if ($exam[$work->id]) {
                    $work = AnnualWork::find($work->id);
                    $exam_arr[$work->max] = $student_cote->cote;
                }
            }

            $cote = 0;
            $i = 0;
            $has_empty = null;

            foreach ($annual_arr as $max => $value) {
                $i++;
                if ($value === null) {
                    $has_empty = true;
                } else {
                    $cote_20 = 20 * $value / $max;
                    $cote += $cote_20;
                }
            }

            if (!$has_empty) {
                $cote_to_save = $cote / $i;
                $student_deliberation = new StudentDeliberation();
                $student_deliberation->student_id = $student->id;

                $student_deliberation->deliberation_id = $promotion_id;
                $student_deliberation->cote = $cote_to_save;

                $student_deliberation->save();
            } else {
                $student_deliberation = new StudentDeliberation();
                $student_deliberation->student_id = $student->id;

                $student_deliberation->deliberation_id = $promotion_id;
                $student_deliberation->cote = null;

                $student_deliberation->save();
            }
        }
        return StudentDeliberation::where('promotion_id', $promotion_id);
    }
}
