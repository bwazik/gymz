<?php

namespace App\Actions\Workout;

use App\Enums\SessionStatus;
use App\Models\WorkoutSession;
use Exception;
use Illuminate\Support\Facades\DB;

class VerifySessionToken
{
    /**
     * @param WorkoutSession $session
     * @param string $token
     * @return void
     * @throws Exception
     */
    public function execute(WorkoutSession $session, string $token): void
    {
        // Ensure session is still Scheduled
        if ($session->status !== SessionStatus::Scheduled) {
            throw new Exception('التمرين دا مش في حالة مجدولة');
        }

        $sessionStartTime = $session->workoutIntent->start_time;
        if (now() < $sessionStartTime->copy()->subMinutes(15)) {
            throw new Exception('لسه بدري! تقدر تعمل سكان قبل التمرينة بـ 15 دقيقة بس.');
        }

        if ($token !== $session->qr_token) {
            throw new Exception('الكود مش صح. جرب تاني.');
        }

        DB::transaction(function () use ($session) {
            $session->update([
                'status' => SessionStatus::InProgress,
                'scanned_at' => now(),
            ]);

            // TODO: [NOTIFICATION] - Notify BOTH users that the session has officially started
        });
    }
}
