<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutRequest;

class ConfirmedSessionCancelledByGuestNotification extends Notification
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
        return $this->workoutRequest->intent_id;
    }
    protected function getNotificationType(): string
    {
        return 'session_cancelled_guest';
    }
    protected function getEntityType(): string
    {
        return 'workout_intent';
    }

    protected function getTitle(): string
    {
        return 'تمرينة اتلغت ⚠️';
    }

    protected function getMessage(): string
    {
        return "الجيماوي {$this->workoutRequest->sender->name} لغى حضوره لتمرينة {$this->workoutRequest->workoutIntent->workoutCategory->name}. تم إرجاع تمرينتك للعامة عشان جيماوي تاني يشاركك.";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-x-mark';
    }

    protected function getIconColor(): string
    {
        return 'danger';
    }
    protected function getActionUrl(): string
    {
        return route('home');
    }
    protected function getActionText(): string
    {
        return 'شوف التمارين';
    }
    protected function getCustomData(): array
    {
        return [
            'intent_id' => $this->workoutRequest->intent_id,
            'guest_name' => $this->workoutRequest->sender->name
        ];
    }
}
