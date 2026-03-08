<?php

namespace App\Notifications\Admin;

use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserOnboardedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(public User $user)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $genderText = $this->user->gender?->getLabel() ?? 'غير محدد';
        $levelText = $this->user->level?->getLabel() ?? 'غير محدد';

        return FilamentNotification::make()
            ->title('چيماوي جديد مكمل بياناته')
            ->body("الچيماوي {$this->user->name} سجل وأكمل بياناته بنجاح.\n- التليفون: {$this->user->phone}\n- النوع: {$genderText}\n- المستوى: {$levelText}")
            ->icon('heroicon-o-user-plus')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
