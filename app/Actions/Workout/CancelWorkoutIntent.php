<?php

namespace App\Actions\Workout;

use App\Enums\SessionStatus;
use App\Models\User;
use App\Models\WorkoutIntent;
use App\Models\WorkoutSession;
use App\Notifications\Admin\SessionCancelledByHostAdminNotification;
use App\Notifications\User\SessionCancelledByHostNotification;
use App\Services\GamificationService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CancelWorkoutIntent
{
    public function __construct(
        private GamificationService $gamificationService
    ) {
    }

    /**
     * Deletes the given intent and its related unaccepted requests.
     * Penalizes host if a session was already established.
     *
     * @param WorkoutIntent $intent
     * @return string Toast notification message
     * @throws Exception
     */
    public function execute(WorkoutIntent $intent): string
    {
        return DB::transaction(function () use ($intent) {
            $session = WorkoutSession::where('intent_id', $intent->id)
                ->whereIn('status', [SessionStatus::Scheduled, SessionStatus::InProgress])
                ->first();

            $message = 'تم إلغاء التمرينة بنجاح.';

            if ($session) {
                $session->update(['status' => SessionStatus::Cancelled_By_Host]);

                if ($guest = User::find($session->user_b_id)) {
                    $guest->notify(new SessionCancelledByHostNotification($intent));
                }
                Notification::send(User::admin()->get(), new SessionCancelledByHostAdminNotification($intent));

                $this->gamificationService->deductReliability($intent->user_id, 5);
                $message = 'تم إلغاء التمرينة وخصم 5% من الموثوقية لإلغاء اتفاق مؤكد.';
            }

            $intent->workoutRequests()->delete();
            $intent->delete();

            return $message;
        });
    }
}
