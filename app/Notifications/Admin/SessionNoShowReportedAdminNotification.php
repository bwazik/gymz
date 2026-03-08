<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class SessionNoShowReportedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public WorkoutSession $session, public int $reporterId)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('No-Show Reported')
            ->body("User ID {$this->reporterId} reported their partner as absent for session #{$this->session->id}.")
            ->icon('heroicon-o-flag')
            ->iconColor('info')
            ->getDatabaseMessage();
    }
}
