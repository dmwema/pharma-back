<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Professor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            DB::table('course_professor')->insert([
                'course_id' => $course->id,
                'professor_id' => $this->prof()->id
            ]);
        }
    
    }

    private function prof () {
        $prof = Professor::find(rand(1,40));
        if ($prof === null) 
            return $this->prof();
        return $prof;
    }
}
