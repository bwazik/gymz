<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class SessionCompletedRewardAdminNotification extends Notification
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
            ->title('تمرينة مكتملة')
            ->body("التمرينة بين چيماوي ID {$this->session->user_a_id} و چيماوي ID {$this->session->user_b_id} انتهت بنجاح وتم صرف المكافآت.")
            ->icon('heroicon-o-check-badge')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
