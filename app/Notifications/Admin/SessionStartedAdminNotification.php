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
            ->title('بدء تمرينة ⏱️')
            ->body("التمرينة بين چيماوي ID {$this->session->user_a_id} و چيماوي ID {$this->session->user_b_id} بدأت رسمياً.")
            ->icon('heroicon-o-play')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
