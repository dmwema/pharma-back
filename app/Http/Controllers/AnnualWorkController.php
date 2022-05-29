<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentWork;
use Illuminate\Http\Request;

class AnnualWorkController extends Controller
{
    public function store(Request $request)
    {
        $work = new AnnualWork();
        $work->date_work = $request->date;
        $work->title = $request->title;
        $work->description = $request->description;
        $work->course_id = $request->course_id;
        $max = 20;
        if ($request->max !== null) {
            $max = $request->max;
        }
        $work->max = $max;

        if ($work->save()) {
            foreach (Student::where('current_promotion_id', $request->promotion_id)->get() as $student) {
                $s_w = new StudentWork();
                $s_w->student_id = $student->id;
                $s_w->work_id = $work->id;
                $s_w->cote = null;
                $s_w->save();
            }
            return [
                'success' => true,
                'message' => 'Epreuve enrégistrée avec succès',
                'courses' => Course::with_annual_works($request->teacher_id)
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enrégistrement'
            ];
        }
    }

    public function destroy(Request $request)
    {
        $work = AnnualWork::find($request->id)->delete();

        return [
            'success' => true,
            'message' => 'Epreuve supprimée avec succès',
            'courses' => Course::with_annual_works($request->teacher_id)
        ];
    }
}
