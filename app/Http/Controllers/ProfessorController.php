<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Course;
use App\Models\LoginAccess;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Http\Request;


class ProfessorController extends Controller
{
    public function courses(Request $request)
    {
        $prof = Professor::findOrfail($request->teacher_id);
        $courses = Course::where('current_prof_id', $prof->id)->get();

        return $courses;
    }

    public function works(Request $request)
    {
        $courses = Course::with_annual_works($request->teacher_id);

        return [
            'courses' => $courses
        ];
    }

    public function works_with(Request $request)
    {
        $courses = Course::with_annual_works_with($request->teacher_id);

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

        $professor->user_id = $user->id;
        $professor->save();

        $user->professor_id = $professor->id;
        if ($user->save()) {
            return [
                "success" => true,
                'profs' => Professor::all(),
                "message" => "Professeur enrégistré avec succès",
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
        $profs =  Professor::all();
        $return = [];

        foreach ($profs as $prof) {
            $user = User::find($prof->user_id);
            $access = LoginAccess::where('user_id', $user->id)->first();

            $return[] = [
                'id' => $prof->id,
                'firstname' => $prof->firstname,
                'lastname' => $prof->lastname,
                'middlename' => $prof->middlename,
                'email' => $user->email,
                'gender' => $prof->gender,
                'user_id' => $prof->user_id,
                'link' => $access ? $access->link : '',
                'secret' => $access ? $access->secret : '',
                'has_logins' => $access === null ? false : true,
            ];
        }

        return $return;
    }

    public function update(Request $request)
    {
        // store professor
        $professor = Professor::find($request->id);

        $user = User::find($professor->user_id);

        if ($request->email !== null) {
            $user->email = $request->email;
        }

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
        $user = User::where('professor_id', $request->id)->first();

        if ($prof->delete() && $user->delete()) {
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
