<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutRequest;

class WorkoutRequestAcceptedNotification extends Notification
{
    use Queueable, NotificationDataStructure;

    public function __construct(public WorkoutRequest $workoutRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['database', \App\Notifications\Channels\FcmChannel::class];
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
        return 'workout_request_accepted';
    }
    protected function getEntityType(): string
    {
        return 'workout_request';
    }

    protected function getTitle(): string
    {
        return 'طلبك اتقبل!';
    }

    protected function getMessage(): string
    {
        return "الجيماوي {$this->workoutRequest->workoutIntent->user->name} وافق إنكم تتمرنوا سوا. استعد للتمرينة!";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-check-circle';
    }

    protected function getIconBg(): string
    {
        return 'bg-gymz-accent';
    }
    protected function getActionUrl(): string
    {
        return route('sessions');
    }
    protected function getActionText(): string
    {
        return 'شوف التمرينة';
    }
    protected function getCustomData(): array
    {
        return [
            'request_id' => $this->workoutRequest->id,
            'host_name' => $this->workoutRequest->workoutIntent->user->name
        ];
    }
}
