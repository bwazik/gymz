<?php

namespace App\Actions\Workout;

use App\Enums\IntentStatus;
use App\Models\WorkoutIntent;
use Illuminate\Support\Facades\Auth;

class CreateWorkoutIntent
{
    /**
     * Creates a new Workout Intent.
     *
     * @param array $data Contains intent details from the form.
     * @return void
     */
    public function execute(array $data): void
    {
        WorkoutIntent::create(array_merge($data, [
            'user_id' => Auth::id(),
            'status' => IntentStatus::Active,
        ]));
    }
}
