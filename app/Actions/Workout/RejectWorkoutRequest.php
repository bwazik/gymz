<?php

namespace App\Actions\Workout;

use App\Enums\RequestStatus;
use App\Models\WorkoutRequest;
use Exception;

class RejectWorkoutRequest
{
    /**
     * @param WorkoutRequest $request
     * @return void
     * @throws Exception
     */
    public function execute(WorkoutRequest $request): void
    {
        if ($request->workoutIntent->start_time < now()) {
            throw new Exception('متقدرش تاخد أكشن لتمرينة وقتها عدى!');
        }

        $request->update(['status' => RequestStatus::Rejected]);

        // TODO: [NOTIFICATION] - Notify GUEST that their request was rejected
    }
}
