<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Course;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\Request;


class ProfessorController extends Controller
{
    public function courses(Request $request)
    {
        $prof = Professor::findOrfail($request->teacher_id);
        return response($prof->courses)
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function works(Request $request)
    {
        $courses = Course::with('annual_works')->where('current_prof_id', $request->teacher_id)->get();

        return [
            'courses' => $courses
        ];
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->professor_id = null;
        $user->student_id = null;
        $user->password = null;
        $user->email = $request->email;
        $user->password = null;

        // store professor
        $professor = new Professor();
        $professor->firstname = ucfirst($request->firstname);
        $professor->lastname = strtoupper($request->lastname);
        $professor->middlename = strtoupper($request->middlename);
        $professor->gender = strtoupper($request->sexe);
        $user->save();

        $professor->user->id;
        $professor->save();

        $user->professor_id = $professor->id;
        if ($user->save()) {
            return [
                "success" => true,
                'profs' => Professor::all(),
                "message" => "Professeur enrégistré avec succès"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Erreu! une erreur est survenie lors de l'enrégistrement"
            ];
        }
    }

    public function index()
    {
        return Professor::all();
    }

    public function update(Request $request)
    {
        $user = User::where('professor_id', $request->id)->first();
        if ($request->email !== null) {
            $user->email = $request->email;
        }

        return $request;

        // store professor
        $professor = Professor::find($request->id);
        if ($request->firstname !== null) {
            $professor->firstname = ucfirst($request->firstname);
        }
        if ($request->lastname !== null) {
            $professor->lastname = strtoupper($request->lastname);
        }
        if ($request->middlename !== null) {
            $professor->middlename = strtoupper($request->middlename);
        }
        if ($request->gender !== null) {
            $professor->gender = strtoupper($request->gender);
        }
        if ($request->gender !== null) {
            $professor->gender = $request->sexe;
        }

        if ($professor->save() && $user->save()) {
            return [
                'success' => true,
                'message' => 'Informations mises à jour avec succès',
                'profs' => Professor::all()
            ];
        }
    }

    public function destroy(Request $request)
    {
        $prof = Professor::find($request->id);

        if ($prof->delete()) {
            return [
                'success' => true,
                'message' => "Professeur supprimé avec succès",
                'profs' => Professor::all()
            ];
        } else {
            return [
                'sucess' => false,
                'message' => "Echec"
            ];
        }
    }
}
