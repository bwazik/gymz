<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutSession;

class SessionStartedNotification extends Notification
{
    use Queueable, NotificationDataStructure;

    public function __construct(public WorkoutSession $session)
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
        return $this->session->id;
    }
    protected function getNotificationType(): string
    {
        return 'session_started';
    }
    protected function getEntityType(): string
    {
        return 'workout_session';
    }

    protected function getTitle(): string
    {
        return 'التمرينة بدأت! 🔥';
    }

    protected function getMessage(): string
    {
        return "تم تأكيد الحضور والتمرينة بدأت رسمياً! وحوش 💪";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-play';
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
        return 'متابعة التمرينة';
    }
    protected function getCustomData(): array
    {
        return [
            'session_id' => $this->session->id
        ];
    }
}
