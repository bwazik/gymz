<?php

namespace Database\Seeders;

use App\Models\WorkoutCategory;
use App\Models\WorkoutTarget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkoutCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bodybuilding = WorkoutCategory::firstOrCreate(['name' => 'كمال أجسام']);
        
        foreach (['Push', 'Pull', 'Legs', 'Full Body'] as $target) {
            WorkoutTarget::firstOrCreate([
                'workout_category_id' => $bodybuilding->id,
                'name' => $target,
            ]);
        }

        $calisthenics = WorkoutCategory::firstOrCreate(['name' => 'كالستنكس']);
        
        foreach (['Statics', 'Dynamics', 'Skills'] as $target) {
            WorkoutTarget::firstOrCreate([
                'workout_category_id' => $calisthenics->id,
                'name' => $target,
            ]);
        }
    }
}
