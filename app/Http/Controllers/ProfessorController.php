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

        return $request->middlename;

        // store professor
        $professor = new Professor();
        $professor->firstname = $request->firstname;
        $professor->lastname = $request->lastname;
        $professor->middlename = $request->middlename;
        $professor->gender = $request->sexe;
        $professor->user_id = $user->save();

        if ($user->professor_id = $professor->save()) {
            return [
                "success" => true,
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
}
