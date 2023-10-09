<?php

namespace Database\Factories;

use App\Models\possible_answer;
use App\Models\questions;
use App\Models\quizzes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class user_answerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'possible_answer_id' =>    function (){
                return possible_answer::inRandomOrder()->first()->id;
            },
            'question_id' =>    function (){
                return questions::inRandomOrder()->first()->id;
            },
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
