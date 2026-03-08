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
            ->title('New Referral Signup')
            ->body("User {$this->newUser->name} joined using {$this->referrer->name}'s referral code.")
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
