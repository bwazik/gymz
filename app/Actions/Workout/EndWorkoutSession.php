<?php

namespace App\Actions\Workout;

use App\Enums\SessionStatus;
use App\Models\User;
use App\Models\WorkoutSession;
use App\Services\GamificationService;
use Exception;
use Illuminate\Support\Facades\DB;

class EndWorkoutSession
{
    public function __construct(
        private GamificationService $gamificationService
    ) {
    }

    /**
     * @param WorkoutSession $session
     * @return void
     * @throws Exception
     */
    public function execute(WorkoutSession $session): void
    {
        if ($session->status !== SessionStatus::InProgress) {
            throw new Exception('الجلسة غير نشطة حالياً.');
        }

        // ANTI-CHEAT: Minimum 90 minutes
        $minutesPassed = $session->scanned_at && $session->scanned_at->isPast()
            ? $session->scanned_at->diffInMinutes(now())
            : 0;

        if ($minutesPassed < 90) {
            throw new Exception('التمرينة لازم تكون ٩٠ دقيقة على الأقل عشان تاخد النقط 🏋️');
        }

        DB::transaction(function () use ($session) {
            $session->update(['status' => SessionStatus::Completed]);

            $gymName = $session->workoutIntent->gym->name;
            $description = "تمرينة في {$gymName}";

            foreach ([$session->user_a_id, $session->user_b_id] as $userId) {
                // Award 10 glutes
                $this->gamificationService->awardGlutes($userId, 10, $description);

                // Increase reliability by 3 (max 100)
                $this->gamificationService->addReliability($userId, 3);
            }

            // TODO :: NOTIFICATION - Notify BOTH users of the reward - 10 Glutes & Reliability increase

            // Proof of Sweat: If this is their very first completed session, trigger referral rewards
            foreach ([$session->user_a_id, $session->user_b_id] as $userId) {
                // Check total completed sessions for this user
                $completedSessionsCount = WorkoutSession::where(function ($q) use ($userId) {
                    $q->where('user_a_id', $userId)->orWhere('user_b_id', $userId);
                })->where('status', SessionStatus::Completed)->count();

                if ($completedSessionsCount === 1) {
                    $user = User::find($userId);
                    if ($user && $user->referred_by) {
                        $this->gamificationService->processReferralReward($user);
                    }
                }
            }
        });
    }
}
