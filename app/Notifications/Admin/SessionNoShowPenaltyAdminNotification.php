<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class SessionNoShowPenaltyAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public WorkoutSession $session, public int $absentUserId)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('No-Show Penalty Applied')
            ->body("User ID {$this->absentUserId} received a penalty for missing their session.")
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
