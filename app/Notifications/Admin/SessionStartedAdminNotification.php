<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class SessionStartedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public WorkoutSession $session)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('Session Started')
            ->body("Workout session between ID {$this->session->user_a_id} and ID {$this->session->user_b_id} officially started.")
            ->icon('heroicon-o-play')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
