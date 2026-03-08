<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user): void
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (User::where('referral_code', $code)->exists());

        $user->referral_code = $code;
    }

    public function updating(User $user): void
    {
        if ($user->isDirty('image_path') && $user->getOriginal('image_path')) {
            Storage::disk('public')->delete($user->getOriginal('image_path'));
        }
    }

    public function forceDeleted(User $user): void
    {
        if ($user->image_path) {
            Storage::disk('public')->delete($user->image_path);
        }
    }
}
