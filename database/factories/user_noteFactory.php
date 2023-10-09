<?php

namespace Database\Factories;

use App\Models\quizzes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class user_noteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'note' => $this->faker->randomNumber(2),
            'quiz_id' =>    function (){
                return quizzes::inRandomOrder()->first()->id;
            },
            'user_id' =>    function (){
                return User::inRandomOrder()->first()->id;
            },
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}
