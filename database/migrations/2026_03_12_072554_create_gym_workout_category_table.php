<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gym_workout_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workout_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['gym_id', 'workout_category_id']);
        });

        // Safe production dataseeding: Attach all categories to all gyms
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
            \Illuminate\Support\Facades\DB::table('gym_workout_category')->insert($pivotData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_workout_category');
    }
};
