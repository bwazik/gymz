<?php

namespace App\Actions\Workout;

use App\Enums\IntentStatus;
use App\Enums\RequestStatus;
use App\Enums\SessionStatus;
use App\Models\WorkoutRequest;
use App\Models\WorkoutSession;
use App\Services\GamificationService;
use Exception;
use Illuminate\Support\Facades\DB;

class CancelSentWorkoutRequest
{
    public function __construct(
        private GamificationService $gamificationService
    ) {
    }

    /**
     * @param WorkoutRequest $request
     * @return string The message to display to the user
     * @throws Exception
     */
    public function execute(WorkoutRequest $request): string
    {
        if ($request->workoutIntent && $request->workoutIntent->start_time < now()) {
            throw new Exception('متقدرش تلغي طلب لتمرينة وقتها عدى!');
        }

        if ($request->status === RequestStatus::Rejected) {
            return ''; // Do nothing silently
        }

        return DB::transaction(function () use ($request) {
            if ($request->status === RequestStatus::Pending) {
                $request->delete();
                // TODO: [NOTIFICATION] - Notify HOST that the guest withdrew their request
                return 'تم سحب الطلب بنجاح';
            }

            if ($request->status === RequestStatus::Accepted) {
                // Penalize Guest
                $this->gamificationService->deductReliability($request->sender_id, 5);

                // Make the session obsolete and intent available again
                $session = WorkoutSession::where('intent_id', $request->intent_id)
                    ->where('user_b_id', $request->sender_id)
                    ->where('status', SessionStatus::Scheduled)
                    ->first();

                if ($session) {
                    $session->update(['status' => SessionStatus::Cancelled_By_Guest]);
                }

                // Mark intent as active again so someone else can match
                $request->workoutIntent->update(['status' => IntentStatus::Active]);

                $request->delete();

                // TODO: [NOTIFICATION] - Notify HOST that the guest cancelled the confirmed session
                return 'تم إلغاء الانضمام وخصم 5% من الموثوقية لأن الطلب كان مقبول.';
            }

            return '';
        });
    }
}
