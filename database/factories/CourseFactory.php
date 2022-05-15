<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Professor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    private function prof () {
        $prof = Professor::find(rand(1,40));
        if ($prof === null) 
            return $this->prof();
        return $prof;
    }

    public function definition(): array
    {
    	return [
    	    'title' => $this->faker->name,
            'ponderation' => rand(2,6),
    	];
    }
}
