<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Professor;
use App\Models\Promotion;
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

        if ($request->current_prof_id !== null) {
            $course->current_prof_id = ucfirst($request->current_prof_id);
        }

        if ($request->current_promotion_id !== null) {
            $course->current_promotion_id = ucfirst($request->current_promotion_id);
        }

        if ($course->save()) {
            return [
                'success' => true,
                'message' => 'Informations mises à jour avec succès',
                'courses' => $this->get_all($request)
            ];
        }
    }
}
