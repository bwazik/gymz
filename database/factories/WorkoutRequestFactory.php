<?php

namespace Database\Factories;

use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutRequest>
 */
class WorkoutRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->randomElement([RequestStatus::PENDING, RequestStatus::ACCEPTED, RequestStatus::REJECTED]),
        ];
    }
}
