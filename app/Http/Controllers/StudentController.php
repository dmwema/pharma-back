<?php

namespace App\Http\Controllers;

use App\Models\LoginAccess;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private function get_students($promotion_id)
    {
        $students =  Student::where('current_promotion_id', $promotion_id)->get();
        $return = [];

        foreach ($students as $student) {
            $user = User::find($student->user_id);
            $access = LoginAccess::where('user_id', $user->id)->first();

            $return[] = [
                'id' => $student->id,
                'firstname' => $student->firstname,
                'lastname' => $student->lastname,
                'middlename' => $student->middlename,
                'email' => $user->email,
                'gender' => $student->gender,
                'user_id' => $student->user_id,
                'has_logins' => $access === null ? false : true,
            ];
        }

        return $return;
    }

    public function index(Request $request)
    {
        return $this->get_students($request->promotion_id);
    }

    public function store(Request $request)
    {
        return $request->email;
        $user = new User();
        $user->student_id = null;
        $user->professor_id = null;
        $user->password = null;
        $user->email = $request->email;
        $user->password = null;

        // store student
        $student = new Student();
        $student->firstname = ucfirst($request->firstname);
        $student->lastname = strtoupper($request->lastname);
        $student->middlename = strtoupper($request->middlename);
        $student->gender = strtoupper($request->sexe);
        $user->save();

        $student->user_id = $user->id;
        $student->save();

        $user->student_id = $student->id;
        if ($user->save()) {
            return [
                "success" => true,
                'students' => $this->get_students($request->promotion_id),
                "message" => "Etudiant enrégistré avec succès"
            ];
        } else {
            return [
                "success" => false,
                "message" => "Erreu! une erreur est survenie lors de l'enrégistrement"
            ];
        }
    }

    public function update(Request $request)
    {
        // store student
        $student = Student::find($request->id);

        $user = User::find($student->user_id);

        if ($request->email !== null) {
            $user->email = $request->email;
        }

        if ($request->firstname !== null) {
            $student->firstname = ucfirst($request->firstname);
        }
        if ($request->lastname !== null) {
            $student->lastname = strtoupper($request->lastname);
        }
        if ($request->middlename !== null) {
            $student->middlename = strtoupper($request->middlename);
        }
        if ($request->gender !== null) {
            $student->gender = strtoupper($request->gender);
        }

        if ($student->save() && $user->save()) {
            return [
                'success' => true,
                'message' => 'Informations mises à jour avec succès',
                'students' => $this->get_students($request->promotion_id)
            ];
        }
    }

    public function destroy(Request $request)
    {
        $student = Student::find($request->id);
        $user = User::where('student_id', $request->id)->first();

        if ($student->delete() && $user->delete()) {
            return [
                'success' => true,
                'message' => "Étudiant supprimé de la base de données avec succès",
                'students' => $this->get_students($request->promotion_id)
            ];
        } else {
            return [
                'sucess' => false,
                'message' => "Echec"
            ];
        }
    }
}
