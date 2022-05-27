<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!$token = Auth::attempt($request->only('email', 'password'))) {
            return response(null, 401);
        }

        return compact('token');
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return $user;
    }

    public function logout(Request $request)
    {
        auth()->logout();
    }
}
