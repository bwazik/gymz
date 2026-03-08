<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\WorkoutSession;

class SessionCompletedRewardNotification extends Notification
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
        return 'session_completed_reward';
    }
    protected function getEntityType(): string
    {
        return 'workout_session';
    }

    protected function getTitle(): string
    {
        return 'عاش يا بطل! 🏆';
    }

    protected function getMessage(): string
    {
        return "عاش التمرينة كملت! كسبت 10 جلوتس ونسبة الموثوقية عندك زادت. استمر 🚀";
    }

    protected function getIcon(): string
    {
        return 'heroicon-o-check-badge';
    }

    protected function getIconColor(): string
    {
        return 'success';
    }
    protected function getActionUrl(): string
    {
        return route('wallet');
    }
    protected function getActionText(): string
    {
        return 'المحفظة';
    }
    protected function getCustomData(): array
    {
        return [
            'session_id' => $this->session->id,
            'glutes_earned' => 10
        ];
    }
}
