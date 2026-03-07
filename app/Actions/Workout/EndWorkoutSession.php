<?php

namespace App\Actions\Workout;

use App\Enums\SessionStatus;
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
        if ($session->scanned_at && $session->scanned_at->diffInMinutes(now(), false) < 90) {
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

            // TODO: [NOTIFICATION] - Notify BOTH users of the reward - 10 Glutes & Reliability increase
        });
    }
}
