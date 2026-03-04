<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\User;
use App\Models\WorkoutIntent;
use App\Models\WorkoutTarget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkoutIntentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $gyms = Gym::all();
        $targets = WorkoutTarget::all();

        if ($users->isEmpty() || $gyms->isEmpty() || $targets->isEmpty()) {
            return;
        }

        WorkoutIntent::factory(25)->state(function () use ($users, $gyms, $targets) {
            return [
                'user_id' => $users->random()->id,
                'gym_id' => $gyms->random()->id,
                'workout_target_id' => $targets->random()->id,
            ];
        })->create();
    }
}
