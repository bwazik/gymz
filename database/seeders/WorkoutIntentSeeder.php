<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\User;
use App\Models\WorkoutCategory;
use App\Models\WorkoutIntent;
use App\Models\WorkoutTarget;
use App\Traits\TruncatableTables;
use Illuminate\Database\Seeder;

class WorkoutIntentSeeder extends Seeder
{
    use TruncatableTables;

    public function run(): void
    {
        $this->truncateTables(['workout_intents']);

        $users = User::all();
        $gyms = Gym::all();
        $categories = WorkoutCategory::all();
        $targets = WorkoutTarget::all();

        if ($users->isEmpty() || $gyms->isEmpty() || $targets->isEmpty()) {
            return;
        }

        WorkoutIntent::factory(25)->state(function () use ($users, $gyms, $categories, $targets) {
            return [
                'user_id' => $users->random()->id,
                'gym_id' => $gyms->random()->id,
                'workout_category_id' => $categories->random()->id,
                'workout_target_id' => $targets->random()->id,
            ];
        })->create();
    }
}
