<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Course;
use App\Models\Deliberation;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentDeliberation;
use App\Models\StudentWork;
use Illuminate\Http\Request;

class StudentDeliberationController extends Controller
{
    public function sendCotes(Request $request)
    {
        $course = Course::find($request->course_id);
        $delib = Deliberation::find($request->session_id);

        $works = AnnualWork::where('course_id', $course->id)->where('session_id', null)->get();
        $exam = AnnualWork::where('course_id', $course->id)->where('session_id', $delib->session_id)->first();

        $students = Student::where('current_promotion_id', $course->current_promotion_id)->orderBy('lastname')->get();

        foreach ($students as $student) {

            $has_ann_null = false;
            $has_exam_null = false;

            $moy = null;
            $exam_st = null;
            $exam_st = null;

            foreach ($works as $work) {
                $s_w = StudentWork::where('student_id', $student->id)->where('work_id', $work->id)->first();

                if ($s_w === null) {
                    $has_ann_null = true;
                } else {
                    if ($s_w->cote === null) {
                        $has_ann_null = true;
                    }
                }
            }

            $t = 0;
            if (!$has_ann_null) {
                $moy = 0;
                foreach ($works as $work) {
                    $t++;
                    $s_w = StudentWork::where('student_id', $student->id)->where('work_id', $work->id)->first();
                    $moy += $s_w->cote / $work->max;
                }
                $annual_st =  $has_ann_null ? null : ceil($moy * 20 / $t);
            } else {
                $annual_st = null;
            }

            $s_w_e = StudentWork::where('student_id', $student->id)->where('work_id', $exam->id)->first();

            if ($s_w_e === null) {
                $exam_st = null;
            } else {
                if ($s_w_e->cote === null) {
                    $exam_st = null;
                }
                $exam_st = $has_exam_null || $has_ann_null ? null : ceil((($moy * 20 / $t) + $s_w_e->cote) / 2); // moy total
            }

            $stud_delib = StudentDeliberation::where('student_id', $student->id)->where('deliberation_id', $delib->id)->first();

            if ($stud_delib === null) {
                $stud_delib = StudentDeliberation::create([
                    'exam' => $exam_st,
                    'annual' => $annual_st,
                    'student_id' => $student->id,
                    'deliberation_id' => $delib->id,
                    'cote' => ceil(($annual_st + $exam_st) / 2)
                ]);
            }
        }

        return [
            'success' => true,
            'message' => 'Cotes envoyés avec succès'
        ];
    }
}
