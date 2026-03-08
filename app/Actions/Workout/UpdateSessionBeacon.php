<?php

namespace App\Actions\Workout;

use App\Enums\SessionStatus;
use App\Models\WorkoutSession;
use Exception;

class UpdateSessionBeacon
{
    /**
     * @param WorkoutSession $session
     * @param int $userId
     * @param string $color
     * @param string $location
     * @return void
     * @throws Exception
     */
    public function execute(WorkoutSession $session, int $userId, string $color, string $location): void
    {
        // Only allow status updates if the session is scheduled
        // (If it's in progress, they already met)
        if ($session->status !== SessionStatus::Scheduled) {
            throw new Exception('لا يمكن تحديث حالتك إلا قبل بدء التمرينة.');
        }

        // Only allow updates within 15 minutes of the workout start time
        if ($session->workoutIntent->start_time > now()->addMinutes(15)) {
            throw new Exception('تقدر تحدث الرادار بتاعك قبل التمرينة بـ 15 دقيقة بس.');
        }

        $statusData = [
            'color' => $color,
            'location' => $location,
            'updated_at' => now()->toIso8601String(),
        ];

        if ($session->user_a_id === $userId) {
            $session->update(['user_a_status' => $statusData]);
        } elseif ($session->user_b_id === $userId) {
            $session->update(['user_b_status' => $statusData]);
        } else {
            throw new Exception('أنت لست طرفاً في هذه الجلسة.');
        }
    }
}
