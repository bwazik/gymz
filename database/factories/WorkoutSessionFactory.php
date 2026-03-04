<?php

namespace Database\Factories;

use App\Enums\SessionStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutSession>
 */
class WorkoutSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'qr_token' => Str::random(32),
            'scanned_at' => null,
            'status' => fake()->randomElement([SessionStatus::Scheduled, SessionStatus::InProgress, SessionStatus::Completed, SessionStatus::Disputed]),
        ];
    }
}
