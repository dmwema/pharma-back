<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Course;
use App\Models\ExamSchedule;
use App\Models\Professor;
use App\Models\Promotion;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentWork;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    public function store(Request $request)
    {
        $existing = ExamSchedule::where('course_id', $request->course_id)->where('session_id', $request->session_id)->get();

        if (count($existing) === 0) {
            $schedule = new ExamSchedule();
            $schedule->course_id = $request->course_id;
            $schedule->session_id = $request->session_id;
            $schedule->date = $request->date;

            $schedule->save();

            $id = $schedule->id;

            $promotion = Promotion::find($request->promotion_id);
            $course = Course::find($schedule->course_id);
            $teacher = Professor::find($course->current_prof_id);
            $session = Session::find($schedule->session_id);
            $schedules = ExamSchedule::where('session_id', $session->id)->get();

            // add work
            if (count(AnnualWork::where('course_id', $request->course_id)->where('session_id', $request->session_id)->get()) === 0) {
                $work = new AnnualWork();
                $work->date_work = $request->date;
                $work->title = "Cotes Examen " . $session->title;
                $work->description = "Cotes de l'examen de la " . $session->title;
                $work->course_id = $request->course_id;
                $work->session_id = $session->id;
                $max = 20;

                if ($work->save()) {
                    foreach (Student::where('current_promotion_id', $request->promotion_id)->get() as $student) {
                        $s_w = new StudentWork();
                        $s_w->student_id = $student->id;
                        $s_w->work_id = $work->id;
                        $s_w->cote = null;
                        $s_w->save();
                    }
                }
            }

            return [
                'success' => true,
                'message' => "Horraire enrégistré avec succès",
                'schedule' => [
                    'id' => $id,
                    'course' => $course->title . ' (' . $teacher->lastname . ' ' . $teacher->firstname . ')',
                    'date' => $schedule->date,
                    'key' => count($schedules),
                ]
            ];
        }

        return [
            'success' => false,
            'message' => "Ce cours est déjà enrégistré dans cet horraire"
        ];
    }

    public function update(Request $request)
    {
        $schedule = ExamSchedule::find($request->id);

        if ($request->course_id !== null) {
            $schedule->course_id = $request->course_id;
        }
        if ($request->date !== null) {
            $schedule->date = $request->date;
        }

        $schedule->save();

        return [
            'success' => true,
            'message' => "Horraire modifiée avec succès"
        ];
    }

    public function destroy(Request $request)
    {
        $schedule = ExamSchedule::find($request->id);

        if ($schedule->delete()) {
            return [
                'success' => true,
                'message' => "Horraire supprimée de la base de données avec succès",
            ];
        } else {
            return [
                'sucess' => false,
                'message' => "Echec"
            ];
        }
    }
}
