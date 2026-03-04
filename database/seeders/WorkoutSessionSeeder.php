<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkoutIntent;
use App\Models\WorkoutSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkoutSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $intents = WorkoutIntent::all();

        if ($users->isEmpty() || $intents->isEmpty()) {
            return;
        }

        // We will create 15 active/completed sessions
        $intentsToAssign = $intents->random(min(15, $intents->count()));

        foreach ($intentsToAssign as $intent) {
            // User A is usually the one who created the intent (or another user, depending on how logic evolves)
            $userA = User::find($intent->user_id);
            
            // Pick a User B who is NOT User A
            $userB = $users->where('id', '!=', $userA->id)->random();

            WorkoutSession::factory()->create([
                'intent_id' => $intent->id,
                'user_a_id' => $userA->id,
                'user_b_id' => $userB->id,
            ]);
        }
    }
}
