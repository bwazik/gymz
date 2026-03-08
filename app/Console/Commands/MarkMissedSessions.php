<?php

namespace App\Console\Commands;

use App\Enums\SessionStatus;
use App\Models\User;
use App\Models\WorkoutSession;
use App\Notifications\Admin\SessionMissedAdminNotification;
use App\Notifications\User\SessionMissedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class MarkMissedSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:mark-missed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks scheduled sessions with start times older than 2 hours as missed and penalizes users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding missed sessions...');

        $missedSessions = WorkoutSession::where('status', SessionStatus::Scheduled)
            ->whereHas('workoutIntent', function ($query) {
                $query->where('start_time', '<', now()->subHours(2));
            })->get();

        if ($missedSessions->isEmpty()) {
            $this->info('No missed sessions found.');
            return;
        }

        $count = 0;
        foreach ($missedSessions as $session) {
            /** @var \App\Models\WorkoutSession $session */
            $session->update(['status' => SessionStatus::Missed]);

            Notification::send(User::admin()->get(), new SessionMissedAdminNotification($session));

            // Notify BOTH users that the session was marked as Missed
            foreach ([$session->user_a_id, $session->user_b_id] as $userId) {
                if ($user = User::find($userId)) {
                    $user->notify(new SessionMissedNotification($session));

                    // Penalize both users for ghosting
                    $user->reliability_score = max(0, $user->reliability_score - 5);
                    $user->save();
                }
            }

            $count++;
        }

        $this->info("Successfully marked {$count} sessions as missed and applied penalties.");
    }
}
