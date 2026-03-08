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
            ->title('تم التبليغ عن غياب')
            ->body("الچيماوي رقم {$this->reporterId} قام بالتبليغ عن غياب الطرف الآخر في التمرينة رقم #{$this->session->id}.")
            ->icon('heroicon-o-flag')
            ->iconColor('info')
            ->getDatabaseMessage();
    }
}
