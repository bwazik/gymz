<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutSession;

class SessionNoShowPenaltyNotification extends Notification
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
        return 'session_no_show_penalty';
    }
    protected function getEntityType(): string
    {
        return 'workout_session';
    }

    protected function getTitle(): string
    {
        return 'خصم موثوقية 📉';
    }

    protected function getMessage(): string
    {
        return "شريكك بلغ إنك مجيتش التمرينة. تم خصم 10% من الموثوقية بتاعتك كعقوبة.";
    }

    protected function getIcon(): string
    {
        return 'alert-triangle';
    }
    protected function getIconBg(): string
    {
        return 'bg-gymz-red';
    }
    protected function getActionUrl(): string
    {
        return route('profile');
    }
    protected function getActionText(): string
    {
        return 'شوف بروفايلك';
    }
    protected function getCustomData(): array
    {
        return [
            'session_id' => $this->session->id,
            'penalty' => 10
        ];
    }
}
