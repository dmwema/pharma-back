<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function verify_pass(Request $request)
    {
        $pass = $request->pass;
        $admin = Admin::find($request->id);

        if (Hash::check($pass, $admin->password)) {
            return [
                "success" => true,
            ];
        } else {
            return [
                "success" => false,
                "message" => "Mot de passe éroné",
            ];
        }
    }
}
