<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class SessionMissedAdminNotification extends Notification
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
            ->title('تمرينة ضائعة (غياب) ⏰')
            ->body("التمرينة بين چيماوي ID {$this->session->user_a_id} و چيماوي ID {$this->session->user_b_id} انتهت بدون حضور الطرفين.")
            ->icon('heroicon-o-clock')
            ->iconColor('warning')
            ->getDatabaseMessage();
    }
}
