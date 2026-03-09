<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutSession;

class SessionMissedNotification extends Notification
{
    use Queueable, NotificationDataStructure;

    public function __construct(public WorkoutSession $session)
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
        return $this->session->id;
    }
    protected function getNotificationType(): string
    {
        return 'session_missed';
    }
    protected function getEntityType(): string
    {
        return 'workout_session';
    }

    protected function getTitle(): string
    {
        return 'تمرينة ضاعت!';
    }

    protected function getMessage(): string
    {
        return "وقت التمرينة عدى ومحدش أكد الحضور. تم تسجيل التمرينة كفائتة. متنسوش المرة الجاية تشغلوا الكيو آر كود!";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-clock';
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
        return 'سجل التمارين';
    }
    protected function getCustomData(): array
    {
        return [
            'session_id' => $this->session->id
        ];
    }
}
