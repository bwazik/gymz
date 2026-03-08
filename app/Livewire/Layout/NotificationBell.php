<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function mount()
    {
        $this->refreshUnreadCount();
    }

    #[On('notifications-updated')]
    public function refreshUnreadCount()
    {
        if (Auth::check()) {
            $this->unreadCount = Auth::user()->unreadNotifications()->count();
        }
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            // Convert to array to avoid Livewire hydration issues with nested model JSON casts
            $this->notifications = Auth::user()->notifications()->take(15)->get()->toArray();
        }
    }

    public function markAsRead($notificationId)
    {
        if (Auth::check()) {
            $notification = Auth::user()->notifications()->find($notificationId);
            if ($notification && is_null($notification->read_at)) {
                $notification->markAsRead();
                $this->unreadCount = max(0, $this->unreadCount - 1);
            }

            // The data is an array since we retrieved from Eloquent
            if ($notification && isset($notification->data['action_url']) && !empty($notification->data['action_url'])) {
                return $this->redirect($notification->data['action_url'], navigate: true);
            }
        }
    }

    public function render()
    {
        return view('livewire.layout.notification-bell');
    }
}
