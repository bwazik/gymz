<?php

namespace App\Notifications\Admin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class ReferralJoinedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public User $newUser, public User $referrer)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('تسجيل عن طريق الإحالة')
            ->body("الچيماوي {$this->newUser->name} سجل باستخدام كود الإحالة الخاص بـ {$this->referrer->name}.")
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
