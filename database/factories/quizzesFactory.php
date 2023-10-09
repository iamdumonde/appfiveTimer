<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class quizzesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->sentence(2),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(70),
            'visibility' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}
