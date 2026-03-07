<?php

namespace App\Traits\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

trait WithRateLimiting
{
    /**
     * Check if the user is rate limited for a specific action.
     * Automatically dispatches a toast message if limited.
     *
     * @param string $actionName (e.g., 'manage-request')
     * @param int $maxAttempts
     * @param int $decaySeconds
     * @return bool True if limited, False if allowed to proceed.
     */
    public function isRateLimited(string $actionName, int $maxAttempts = 5, int $decaySeconds = 60): bool
    {
        $userId = Auth::id();
        $key = "{$actionName}:{$userId}";

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            if (method_exists($this, 'toastError')) {
                $this->toastError("حاولت كتير! استنى {$seconds} ثانية ⏳");
            } else {
                $this->dispatch('toast', message: "حاولت كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            }

            return true;
        }

        RateLimiter::hit($key, $decaySeconds);
        return false;
    }
}
