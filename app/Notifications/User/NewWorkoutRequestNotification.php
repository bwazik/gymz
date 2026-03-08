<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutRequest;

class NewWorkoutRequestNotification extends Notification
{
    use Queueable, NotificationDataStructure;

    public function __construct(public WorkoutRequest $workoutRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return $this->getStandardData();
    }

    protected function getEntityId()
    {
        return $this->workoutRequest->id;
    }
    protected function getNotificationType(): string
    {
        return 'new_workout_request';
    }
    protected function getEntityType(): string
    {
        return 'workout_request';
    }

    protected function getTitle(): string
    {
        return 'طلب تمرين جديد';
    }

    protected function getMessage(): string
    {
        return "الجيماوي {$this->workoutRequest->sender->name} عايز يتمرن معاك في تمرينة {$this->workoutRequest->workoutIntent->workoutCategory->name}.";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-hand-raised';
    }

    protected function getIconBg(): string
    {
        return 'bg-gymz-accent';
    }
    protected function getActionUrl(): string
    {
        return route('requests');
    }
    protected function getActionText(): string
    {
        return 'شوف الطلبات';
    }
    protected function getCustomData(): array
    {
        return [
            'request_id' => $this->workoutRequest->id,
            'sender_name' => $this->workoutRequest->sender->name
        ];
    }
}
