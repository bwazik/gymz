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
            ->title('تطبيق عقوبة غياب')
            ->body("الچيماوي رقم {$this->absentUserId} حصل على عقوبة خصم موثوقية بسبب عدم حضوره التمرينة المجدولة.")
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
