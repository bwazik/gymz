<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutSession;

class SessionNoShowReportedNotification extends Notification
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
        return 'session_no_show_reported';
    }
    protected function getEntityType(): string
    {
        return 'workout_session';
    }

    protected function getTitle(): string
    {
        return 'تم تسجيل الغياب 📝';
    }

    protected function getMessage(): string
    {
        return "شُكراً لالتزامك! تم تسجيل غياب شريكك وخصم الموثوقية منه. متيأسش، دور على جيماوي تاني.";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-flag';
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
        return 'ابحث عن تمرينة';
    }
    protected function getCustomData(): array
    {
        return [
            'session_id' => $this->session->id
        ];
    }
}
