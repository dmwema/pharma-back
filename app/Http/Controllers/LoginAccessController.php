<?php

namespace App\Http\Controllers;

use App\Models\LoginAccess;
use Illuminate\Http\Request;

class LoginAccessController extends Controller
{
    public function generate () {
        $link = $this->randomString(6, 'link');
        $secret = $this->randomString(8, 'secret');

        return [
            'link' => $link,
            'secret' => $secret,
        ];
    }

    private function randomString ($lenght, $type)
    {
        $characters = ['0123456789', 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '#@/%!&*'];
        $randstring = [];

        if ($type == 'link') {
            do {
                $pos = rand(0, 1);
                $randstring[] = $characters[$pos][rand(0, strlen($characters[$pos]) - 1)];
            } while (count($randstring) < $lenght);
            if (count(LoginAccess::where('link', $randstring)->get()) > 0) {
                $this->randomString($lenght, $type);
            }
        } else if ($type == 'secret') {
            do {
                $pos = rand(0, count($characters) - 1);
                $randstring[] = $characters[$pos][rand(0, strlen($characters[$pos]) - 1)];
            } while (count($randstring) < $lenght);
            if (count(LoginAccess::where('secret', $randstring)->get()) > 0) {
                $this->randomString($lenght, $type);
            }
        }

        return implode('', $randstring);
    }

    public function check (Request $request) {
        $link = $request->link;
        $secret = $request->secret;

        $access = LoginAccess::where('link', $link)->where('secret', $secret)->get(); 

        if (count($access) > 0) {
            return [
                'success' => true
            ];
        }

        return [
            'success' => false
        ];
    }

    public function checklink (Request $request) {
        
        $link = $request->link;

        $access = LoginAccess::where('link', $link)->first(); 

        if ($access !== null) {

            if ($access ->is_used) {
                $datas = [
                    'success' => false,
                    'redirect' => true
                ];
            }
            
            $user = $access->user;

            $datas = [
                'success' => true,
                'user' => $user,
                'professor' => $user->professor
            ];

            return $datas;
        }

        return [
            'success' => false,
            'redirect' => false
        ];

    }

}
