<?php

namespace Database\Factories;

use App\Models\Professor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ProfessorFactory extends Factory
{
    protected $model = Professor::class;

    public function definition(): array
    {
        $genders = ['M', 'F'];

        $user = User::create([
            'username' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'professor_id' => null,
            'student_id' => null
        ]);

    	return [
    	    'name' => $this->faker->lastName(),
    	    'firstname' => $this->faker->firstName(),
    	    'lastname' => $this->faker->lastName(),
    	    'gender' => $genders[rand(0,1)],
    	    'grade' => $this->faker->jobTitle(),
    	    'avatar' => $this->faker->imageUrl(),
    	    'user_id' => $user->id,
    	];
    }
}
