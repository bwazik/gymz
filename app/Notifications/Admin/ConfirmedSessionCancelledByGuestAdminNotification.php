<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class ConfirmedSessionCancelledByGuestAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public WorkoutRequest $workoutRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('إلغاء تمرينة مؤكدة (من الضيف)')
            ->body("الچيماوي {$this->workoutRequest->sender->name} ألغى تمرينة مؤكدة مع {$this->workoutRequest->workoutIntent->user->name}.")
            ->icon('heroicon-o-x-mark')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
