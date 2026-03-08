<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class WorkoutRequestWithdrawnAdminNotification extends Notification
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
            ->title('Workout Request Withdrawn')
            ->body("{$this->workoutRequest->sender->name} withdrew their workout request to {$this->workoutRequest->workoutIntent->user->name}.")
            ->icon('heroicon-o-arrow-left-on-rectangle')
            ->iconColor('warning')
            ->getDatabaseMessage();
    }
}
