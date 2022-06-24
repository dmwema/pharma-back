<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function get_all($promotion)
    {
        $return = [];
        $sessions = Session::where('promotion_id', $promotion)->get();
        $i = 0;
        foreach ($sessions as $session) {
            $i++;
            $sch = [];
            $schedules = ExamSchedule::get_all($session->id);
            foreach ($schedules as $schedule) {
                $sch[] = $schedule;
            }
            $return[] = [
                'id' => $session->id,
                'key' => $i,
                'title' => $session->title,
                'start' => $session->start,
                'end' => $session->end,
                'promotion_id' => $session->promotion_id,
                'schedules' => $sch
            ];
        }

        return $return;
    }

    public function index(Request $request)
    {
        return [
            'sessions' => $this->get_all($request->promotion_id),
        ];
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
            'message' => "Session enrégistrée avec succès",
            'sessions' => $this->get_all($request->promotion_id)
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
        $session = Session::find($request->session_id);

        if ($session->delete()) {
            return [
                'success' => true,
                'message' => "Session supprimée de la base de données avec succès",
                'sessions' => $this->get_all($request->promotion_id)
            ];
        } else {
            return [
                'sucess' => false,
                'message' => "Echec"
            ];
        }
    }
}
