<?php

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $loadedCount = 15;
    public $hasMore = false;

    public function mount()
    {
        $this->refreshUnreadCount();
    }

    #[On('notifications-updated')]
    public function refreshUnreadCount()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user) {
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function loadNotifications()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user) {
            $total = $user->notifications()->count();
            // Convert to array to avoid Livewire hydration issues with nested model JSON casts
            $this->notifications = $user->notifications()->take($this->loadedCount)->get()->toArray();
            $this->hasMore = $total > $this->loadedCount;
        }
    }

    public function loadMore()
    {
        $this->loadedCount += 15;
        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->unreadCount = 0;
            $this->loadNotifications();
        }
    }

    public function markAsRead($notificationId)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user) {
            $notification = $user->notifications()->find($notificationId);
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
