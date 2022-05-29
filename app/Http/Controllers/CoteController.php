<?php

namespace App\Http\Controllers;

use App\Models\StudentWork;
use Illuminate\Http\Request;

class CoteController extends Controller
{
    public function index(Request $request)
    {
        return StudentWork::all();
    }
}
