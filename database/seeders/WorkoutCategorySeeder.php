<?php

namespace Database\Seeders;

use App\Models\WorkoutCategory;
use App\Models\WorkoutTarget;
use App\Traits\TruncatableTables;
use Illuminate\Database\Seeder;

class WorkoutCategorySeeder extends Seeder
{
    use TruncatableTables;

    public function run(): void
    {
        $this->truncateTables(['workout_categories', 'workout_targets']);

        $bodybuilding = WorkoutCategory::firstOrCreate(['name' => 'كمال أجسام']);

        foreach (['بوش', 'بول', 'رجل', 'فول بادي'] as $target) {
            WorkoutTarget::firstOrCreate([
                'workout_category_id' => $bodybuilding->id,
                'name' => $target,
            ]);
        }

        $calisthenics = WorkoutCategory::firstOrCreate(['name' => 'كالستنكس']);

        foreach (['ثبات', 'ديناميك', 'مهارات'] as $target) {
            WorkoutTarget::firstOrCreate([
                'workout_category_id' => $calisthenics->id,
                'name' => $target,
            ]);
        }
    }
}
