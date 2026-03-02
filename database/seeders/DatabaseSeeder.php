<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\City;
use App\Models\Gym;
use App\Models\WorkoutCategory;
use App\Models\WorkoutTarget;
use App\Models\WorkoutIntent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $city = City::create(['name' => 'Banha', 'is_active' => true]);

        $gymNames = ['BodyArt', 'Fit&Lift', 'Ego', 'AddFit', 'HanyPower', 'Golden'];
        $gyms = collect();
        foreach ($gymNames as $name) {
            $gyms->push(Gym::create(['city_id' => $city->id, 'name' => $name, 'is_active' => true]));
        }

        $bodybuilding = WorkoutCategory::create(['name' => 'Bodybuilding']);
        $bbTargets = collect();
        foreach (['Push', 'Pull', 'Legs', 'Full Body'] as $target) {
            $bbTargets->push(WorkoutTarget::create(['workout_category_id' => $bodybuilding->id, 'name' => $target]));
        }

        $calisthenics = WorkoutCategory::create(['name' => 'Calisthenics']);
        $calTargets = collect();
        foreach (['Statics', 'Dynamics', 'Skills'] as $target) {
            $calTargets->push(WorkoutTarget::create(['workout_category_id' => $calisthenics->id, 'name' => $target]));
        }

        $allTargets = $bbTargets->merge($calTargets);

        $users = User::factory(10)->create();

        WorkoutIntent::factory(25)->state(function () use ($users, $gyms, $allTargets) {
            return [
                'user_id' => $users->random()->id,
                'gym_id' => $gyms->random()->id,
                'workout_target_id' => $allTargets->random()->id,
            ];
        })->create();
    }
}
