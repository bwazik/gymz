<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
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
