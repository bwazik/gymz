<?php

namespace App\Actions\Workout;

use App\Enums\IntentStatus;
use App\Enums\RequestStatus;
use App\Enums\SessionStatus;
use App\Models\WorkoutRequest;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class AcceptWorkoutRequest
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

        DB::transaction(function () use ($request) {
            // Accept this request
            $request->update(['status' => RequestStatus::Accepted]);

            // Mark intent as Matched
            $request->workoutIntent->update(['status' => IntentStatus::Matched]);

            // Reject all other pending requests for this intent
            WorkoutRequest::where('intent_id', $request->intent_id)
                ->where('id', '!=', $request->id)
                ->where('status', RequestStatus::Pending)
                ->update(['status' => RequestStatus::Rejected]);

            // Create a WorkoutSession
            WorkoutSession::create([
                'intent_id' => $request->intent_id,
                'user_a_id' => $request->workoutIntent->user_id,
                'user_b_id' => $request->sender_id,
                'qr_token' => Str::random(32),
                'status' => SessionStatus::Scheduled,
            ]);

            // TODO: [NOTIFICATION] - Notify GUEST that their request was accepted
        });
    }
}
