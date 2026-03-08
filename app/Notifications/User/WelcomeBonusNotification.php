<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WelcomeBonusNotification extends Notification
{
    use Queueable, NotificationDataStructure;

    public function __construct(public int $glutesAmount)
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
        return null;
    }
    protected function getNotificationType(): string
    {
        return 'welcome_bonus';
    }
    protected function getEntityType(): string
    {
        return 'system';
    }
    protected function getTitle(): string
    {
        return 'هدية ترحيبية 🎁';
    }
    protected function getMessage(): string
    {
        return "أهلاً بيك في GymZ! تم إضافة {$this->glutesAmount} جلوتس لمحفظتك.";
    }
    protected function getIcon(): string
    {
        return 'gift';
    }
    protected function getIconBg(): string
    {
        return 'bg-gymz-accent';
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
        return ['amount' => $this->glutesAmount];
    }
}
