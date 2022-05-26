<?php

namespace App\Http\Controllers;

use App\Models\LoginAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function edit_credentials(Request $request)
    {
        $link = $request->link;
        $email = $request->email;
        $pass = Hash::make($request->password);


        $userLoginAcesses = LoginAccess::where('link', $link)->first();

        $userLoginAcesses->is_used = true;
        $userLoginAcesses->save();

        $user = $userLoginAcesses->user;
        $user->email = $email;
        $user->password = $pass;

        $name = "";

        if ($user->professor !== null) {
            $name = $user->professor->firstname . " " . $user->professor->lastname;
        } else if ($user->student !== null) {
            $name = $user->student->firstname . " " . $user->student->lastname;
        }

        if ($user->save()) {

            //MailController::mail($name, $user->email, $request->password);

            return [
                'saved' => true
            ];
        }
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if ($user === null) {
            return [
                'success' => false,
                'message' => 'Cette addresse mail n\'existe pas dans notre base de données',
            ];
        }

        if ($user->type !== $request->type) {
            return [
                'success' => false,
                'message' => 'Accès refusé',
            ];
        }

        if (!Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Mot de passe incorrect',
            ];
        }

        return [
            'success' => true,
            'user' => $user,
        ];
    }

    public function index()
    {
        return User::all();
    }
}
