<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutIntent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class SessionCancelledByHostAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public WorkoutIntent $workoutIntent)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('Session Cancelled (Host)')
            ->body("Host {$this->workoutIntent->user->name} cancelled their workout intent.")
            ->icon('heroicon-o-x-mark')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
