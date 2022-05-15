<?php

namespace App\Http\Controllers;

use App\Models\AnnualWork;
use App\Models\Course;
use App\Models\Professor;
use Illuminate\Http\Request;


class TeacherController extends Controller
{
    public function courses (Request $request) {
        $prof = Professor::findOrfail($request->teacher_id);
        return response($prof->courses)
            ->header('Access-Control-Allow-Origin', '*');
    } 

    public function works (Request $request) {
        $prof = Professor::findOrfail($request->teacher_id);
        $courses = Course::with('annual_works')->where('current_prof_id', $request->teacher_id)->get();

        return $courses;
        
        return $courses;
        

        $works = AnnualWork::where('course_id', $request->course_id)->get();

        return [
            'courses' => $courses
        ];
    }
}
