<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymWorkoutCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gymIds = \App\Models\Gym::pluck('id')->toArray();
        $categoryIds = \App\Models\WorkoutCategory::pluck('id')->toArray();
        
        $pivotData = [];
        $now = now();
        foreach ($gymIds as $gymId) {
            foreach ($categoryIds as $categoryId) {
                $pivotData[] = [
                    'gym_id' => $gymId,
                    'workout_category_id' => $categoryId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        
        if (!empty($pivotData)) {
            \Illuminate\Support\Facades\DB::table('gym_workout_category')->insertOrIgnore($pivotData);
        }
    }
}
