<?php

namespace App\Actions\Workout;

use App\Enums\RequestStatus;
use App\Models\User;
use App\Models\WorkoutRequest;
use App\Notifications\Admin\WorkoutRequestRejectedAdminNotification;
use App\Notifications\User\WorkoutRequestRejectedNotification;
use Exception;
use Illuminate\Support\Facades\Notification;

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

        $request->sender->notify(new WorkoutRequestRejectedNotification($request));
        Notification::send(User::where('is_admin', true)->get(), new WorkoutRequestRejectedAdminNotification($request));
    }
}
