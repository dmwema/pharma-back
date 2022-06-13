<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    public function store(Request $request)
    {
        $schedule = new ExamSchedule();
        $schedule->course_id = $request->course_id;
        $schedule->session_id = $request->session_id;
        $schedule->date = $request->date;

        $schedule->save();

        return [
            'success' => true,
            'message' => "Horraire enrégistré avec succès"
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
