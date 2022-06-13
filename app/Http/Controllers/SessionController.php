<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function index(Request $request)
    {
        $promotion = $request->promotion;
        $return = [];
        $sessions = Session::where('promotion_id', $promotion)->get();

        foreach ($sessions as $session) {
            $sch = [];
            $schedules = ExamSchedule::where('session_id', $session->id)->get();
            foreach ($schedules as $schedule) {
                $sch[] = $schedule;
            }
            $return[] = [
                'id' => $session->id,
                'title' => $session->title,
                'start' => $session->start,
                'end' => $session->end,
                'promotion_id' => $session->promotion_id,
                'schedules' => $sch
            ];
        }

        return $return;
    }

    public function store(Request $request)
    {
        $session = new Session();
        $session->title = $request->title;
        $session->promotion_id = $request->promotion_id;
        $session->start = $request->start;
        $session->end = $request->end;

        $session->save();

        return [
            'success' => true,
            'message' => "Session enrégistrée avec succès"
        ];
    }

    public function update(Request $request)
    {
        $session = Session::find($request->id);

        if ($request->title !== null) {
            $session->title = $request->title;
        }
        if ($request->start !== null) {
            $session->start = $request->start;
        }
        if ($request->end !== null) {
            $session->end = $request->end;
        }

        $session->save();

        return [
            'success' => true,
            'message' => "Session modifiée avec succès"
        ];
    }

    public function destroy(Request $request)
    {
        $session = Session::find($request->id);

        if ($session->delete()) {
            return [
                'success' => true,
                'message' => "Session supprimée de la base de données avec succès",
            ];
        } else {
            return [
                'sucess' => false,
                'message' => "Echec"
            ];
        }
    }
}
