<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function mail($name, $email, $password)
    {
        $data = ['name' => $name, 'password' => $password, 'email' => $email];

        Mail::send('mail', $data, function ($message) use ($email) {
            $message->to($email)->subject("Lien de connexion");
            $message->from(env("MAIL_FROM"), env("MAIL_FROM_NAME"));
        });
    }

    public function teste()
    {
        $data = ['name' => "Daniel Mwema", 'password' => "azeaze", 'email' => "danielmwemakapwe@gmail.com"];

        Mail::send('mail', $data, function ($message) {
            $message->to("danielmwemakapwe@gmail.com")->subject("Lien de connexion");
            $message->from(env("MAIL_FROM"), env("MAIL_FROM_NAME"));
        });
    }
}
