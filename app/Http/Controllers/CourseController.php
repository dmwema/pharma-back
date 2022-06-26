<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Course;
use App\Models\Deliberation;
use App\Models\Professor;
use App\Models\Promotion;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentWork;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private function get_all($request)
    {
        $courses = Course::where('current_promotion_id', $request->promotion_id ?? 1)->get();
        $return = [];

        foreach ($courses as $course) {
            $professor = Professor::find($course->current_prof_id);
            $return[] = [
                'id' => $course->id,
                'title' => $course->title,
                'ponderation' => $course->ponderation,
                'professor_id' => $course->current_prof_id,
                'promotion_id' => $course->current_promotion_id,
                'professor' => $professor == null ? "Non affecté" : $professor->lastname . " " . $professor->middlename . " " . $professor->firstname,
                'professors' => Professor::all(),
            ];
        }

        return $return;
    }

    public function index(Request $request)
    {
        return $this->get_all($request);
    }

    public function store(Request $request)
    {
        // store couse
        $course = new Course();
        $course->title = ucfirst($request->title);
        $course->ponderation = $request->ponderation;

        //professor
        $course->current_prof_id = $request->current_prof_id;

        //promotion
        $course->current_promotion_id = $request->current_promotion_id;

        if ($course->save()) {
            return [
                "success" => true,
                'courses' => $this->get_all($request),
                "message" => "Cours enrégistré avec succès"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Erreu! une erreur est survenie lors de l'enrégistrement"
            ];
        }
    }

    public function destroy(Request $request)
    {
        $course = Course::find($request->id);

        if ($course->delete()) {
            return [
                'success' => true,
                'message' => "Cours supprimé avec succès",
                'courses' => $this->get_all($request)
            ];
        } else {
            return [
                'sucess' => false,
                'message' => "Echec"
            ];
        }
    }

    public function update(Request $request)
    {
        // store professor
        $course = Course::find($request->id);

        if ($request->title !== null) {
            $course->title = ucfirst($request->title);
        }

        if ($request->ponderation !== null) {
            $course->ponderation = ucfirst($request->ponderation);
        }

        if ($request->professor !== null) {
            $course->current_prof_id = $request->professor;
        }

        if ($course->save()) {
            return [
                'success' => true,
                'message' => 'Informations mises à jour avec succès',
                'courses' => $this->get_all($request)
            ];
        }
    }

    public function all_course_cotes(Request $request)
    {
        $course = Course::find($request->course_id);
        $students = Student::where('current_promotion_id', $course->current_promotion_id)->orderBy('lastname')->get();
        $delib = Deliberation::find($request->session_id);

        $i = 0;

        $return = [];

        $columns = [
            'Etudiant'
        ];

        $works = AnnualWork::where('course_id', $course->id)->where('session_id', null)->get();
        $exam = AnnualWork::where('course_id', $course->id)->where('session_id', $delib->session_id)->first();

        foreach ($works as $work) {
            $columns[] = $work->title;
        }
        $columns[] = 'TOT. ANN.';
        $columns[] = 'EXAM.';
        $columns[] = 'MOY.';

        foreach ($students as $student) {
            $ret_et = [];
            $ret_et[] = $student->lastname . ' ' . $student->middlename . ' ' . $student->firstname;
            $i++;

            $has_ann_null = false;
            $has_exam_null = false;

            $j = 0;

            $moy = null;

            foreach ($works as $work) {
                $s_w = StudentWork::where('student_id', $student->id)->where('work_id', $work->id)->first();

                if ($s_w === null) {
                    $has_ann_null = true;
                    $ret_et[] = null;
                } else {
                    if ($s_w->cote === null) {
                        $has_ann_null = true;
                    }
                    $ret_et[] = $s_w->cote;
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
            }

            $s_w_e = StudentWork::where('student_id', $student->id)->where('work_id', $exam->id)->first();

            if ($s_w_e === null) {
                $has_exam_null = true;
                $ret_et[] = null; // moy ann
                $ret_et[] = null; //exam
                $ret_et[] = null; // moy total
            } else {
                if ($s_w_e->cote === null) {
                    $has_exam_null = true;
                }
                $ret_et[] = $has_ann_null ? null : ceil($moy * 20 / $t); // moy ann
                $ret_et[] = $s_w_e->cote; //exam
                $ret_et[] = $has_exam_null || $has_ann_null ? null : ceil((($moy * 20 / $t) + $s_w_e->cote) / 2); // moy total
            }

            $return[] = $ret_et;
        }

        return [
            'datas' => $return,
            'columns' => $columns
        ];
    }
}
