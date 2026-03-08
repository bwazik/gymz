<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class WelcomeBonusAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public int $glutesAmount, public string $userName)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('بونص الترحيب 🎁')
            ->body("الچيماوي {$this->userName} حصل على بونص ترحيب {$this->glutesAmount} جروتز.")
            ->icon('heroicon-o-gift')
            ->iconColor('primary')
            ->getDatabaseMessage();
    }
}
