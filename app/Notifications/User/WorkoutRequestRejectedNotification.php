<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutRequest;

class WorkoutRequestRejectedNotification extends Notification
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
        return 'workout_request_rejected';
    }
    protected function getEntityType(): string
    {
        return 'workout_request';
    }

    protected function getTitle(): string
    {
        return 'طلب مرفوض ❌';
    }

    protected function getMessage(): string
    {
        return "الجيماوي {$this->workoutRequest->workoutIntent->user->name} اعتذر عن تمرينة {$this->workoutRequest->workoutIntent->workoutCategory->name}. دور على جيماوي تاني!";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-x-circle';
    }
    protected function getIconBg(): string
    {
        return 'bg-gymz-accent';
    }
    protected function getActionUrl(): string
    {
        return route('home');
    }
    protected function getActionText(): string
    {
        return 'تمارين تانية';
    }
    protected function getCustomData(): array
    {
        return [
            'request_id' => $this->workoutRequest->id,
            'host_name' => $this->workoutRequest->workoutIntent->user->name
        ];
    }
}
