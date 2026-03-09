<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\FcmChannel;
use App\Models\WorkoutIntent;

class SessionCancelledByHostNotification extends Notification
{
    use Queueable, NotificationDataStructure;

    public function __construct(public WorkoutIntent $workoutIntent)
    {
    }

    public function via($notifiable): array
    {
        return ['database', FcmChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return $this->getStandardData();
    }

    protected function getEntityId()
    {
        return $this->workoutIntent->id;
    }
    protected function getNotificationType(): string
    {
        return 'session_cancelled_host';
    }
    protected function getEntityType(): string
    {
        return 'workout_intent';
    }

    protected function getTitle(): string
    {
        return 'التمرينة اتلغت';
    }

    protected function getMessage(): string
    {
        return "الجيماوي {$this->workoutIntent->user->name} لغى تمرينة {$this->workoutIntent->workoutCategory->name}. دور على تمرينة تانية!";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-x-mark';
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
            'intent_id' => $this->workoutIntent->id,
            'host_name' => $this->workoutIntent->user->name
        ];
    }
}
