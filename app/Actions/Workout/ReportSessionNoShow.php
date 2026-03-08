<?php

namespace App\Actions\Workout;

use App\Enums\SessionStatus;
use App\Models\User;
use App\Models\WorkoutSession;
use App\Notifications\Admin\SessionNoShowPenaltyAdminNotification;
use App\Notifications\Admin\SessionNoShowReportedAdminNotification;
use App\Notifications\User\SessionNoShowPenaltyNotification;
use App\Notifications\User\SessionNoShowReportedNotification;
use App\Services\GamificationService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

        DB::transaction(function () use ($session, $absentUserId, $reporterId) {
            $this->gamificationService->deductReliability($absentUserId, 5);
            $session->update(['status' => SessionStatus::Missed]);

            if ($absentUser = User::find($absentUserId)) {
                $absentUser->notify(new SessionNoShowPenaltyNotification($session));
            }
            if ($reporterUser = User::find($reporterId)) {
                $reporterUser->notify(new SessionNoShowReportedNotification($session));
            }

            Notification::send(User::where('is_admin', true)->get(), new SessionNoShowPenaltyAdminNotification($session, $absentUserId));
            Notification::send(User::where('is_admin', true)->get(), new SessionNoShowReportedAdminNotification($session, $reporterId));
        });
    }
}
