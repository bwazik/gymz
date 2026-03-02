<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\IntentStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutIntent>
 */
class WorkoutIntentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_time' => fake()->dateTimeBetween('now', '+24 hours'),
            'has_invitation' => fake()->boolean(20),
            'status' => IntentStatus::Active,
        ];
    }
}
