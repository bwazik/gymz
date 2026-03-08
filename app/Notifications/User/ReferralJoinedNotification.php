<?php

namespace App\Notifications\User;

use App\Traits\NotificationDataStructure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ReferralJoinedNotification extends Notification
{
    use Queueable, NotificationDataStructure;

    public function __construct(public User $newUser)
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
        return $this->newUser->id;
    }
    protected function getNotificationType(): string
    {
        return 'referral_joined';
    }
    protected function getEntityType(): string
    {
        return 'user';
    }
    protected function getTitle(): string
    {
        return 'جيماوي جديد انضم بسببك! 🚀';
    }
    protected function getMessage(): string
    {
        return "صاحبك {$this->newUser->name} سجل عن طريقك. انزلوا تمرينة سوا عشان تاخدوا 10 جلوتس هدية!";
    }
    protected function getIcon(): string
    {
        return 'user-plus';
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
        return 'استكشف التمارين';
    }
    protected function getCustomData(): array
    {
        return ['new_user_id' => $this->newUser->id];
    }
}
