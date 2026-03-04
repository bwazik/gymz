<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkoutIntent;
use App\Models\WorkoutRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkoutRequestSeeder extends Seeder
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

        // Create 30 random requests
        WorkoutRequest::factory(30)->state(function () use ($users, $intents) {
            $intent = $intents->random();
            // Ensure sender is not the creator of the intent
            $sender = $users->where('id', '!=', $intent->user_id)->random();

            return [
                'intent_id' => $intent->id,
                'sender_id' => $sender->id,
            ];
        })->create();
    }
}
