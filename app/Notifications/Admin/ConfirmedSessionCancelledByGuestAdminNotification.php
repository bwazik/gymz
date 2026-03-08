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
            ->title('Confirmed Session Cancelled (Guest)')
            ->body("Guest {$this->workoutRequest->sender->name} cancelled a confirmed session with {$this->workoutRequest->workoutIntent->user->name}.")
            ->icon('heroicon-o-x-mark')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
