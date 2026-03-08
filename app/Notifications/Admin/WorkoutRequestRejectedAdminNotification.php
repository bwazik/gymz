<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class WorkoutRequestRejectedAdminNotification extends Notification
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
            ->title('Workout Request Rejected')
            ->body("{$this->workoutRequest->workoutIntent->user->name} rejected {$this->workoutRequest->sender->name}'s workout request.")
            ->icon('heroicon-o-x-circle')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
