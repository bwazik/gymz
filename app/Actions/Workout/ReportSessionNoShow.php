<?php

namespace App\Actions\Workout;

use App\Enums\SessionStatus;
use App\Models\WorkoutSession;
use App\Services\GamificationService;
use Exception;
use Illuminate\Support\Facades\DB;

class ReportSessionNoShow
{
    public function __construct(
        private GamificationService $gamificationService
    ) {
    }

    /**
     * @param WorkoutSession $session
     * @param int $reporterId
     * @return void
     * @throws Exception
     */
    public function execute(WorkoutSession $session, int $reporterId): void
    {
        if ($session->status !== SessionStatus::Scheduled) {
            throw new Exception('التمرين دا مش مجدول');
        }

        if (!$session->workoutIntent) {
            throw new Exception('التمرينة مش موجودة');
        }

        $sessionStartTime = $session->workoutIntent->start_time;
        if (now() < $sessionStartTime->copy()->addMinutes(15)) {
            throw new Exception('لازم تستنى 15 دقيقة من ميعاد التمرينة');
        }

        $absentUserId = ($session->user_a_id === $reporterId) ? $session->user_b_id : $session->user_a_id;

        DB::transaction(function () use ($session, $absentUserId) {
            $this->gamificationService->deductReliability($absentUserId, 5);
            $session->update(['status' => SessionStatus::Missed]);

            // TODO: [NOTIFICATION] - Notify the ABSENT user about the penalty and the REPORTER that it's processed
        });
    }
}
