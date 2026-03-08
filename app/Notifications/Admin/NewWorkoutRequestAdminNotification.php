<?php

namespace App\Notifications\Admin;

use App\Models\WorkoutRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class NewWorkoutRequestAdminNotification extends Notification
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
            ->title('طلب انضمام لتمرينة')
            ->body("الچيماوي {$this->workoutRequest->sender->name} طلب الانضمام لتمرينة {$this->workoutRequest->workoutIntent->user->name}.")
            ->icon('heroicon-o-hand-raised')
            ->iconColor('info')
            ->getDatabaseMessage();
    }
}
