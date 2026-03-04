<?php

namespace App\Observers;

use App\Models\Gym;
use Illuminate\Support\Facades\Storage;

class GymObserver
{
    public function updating(Gym $gym): void
    {
        if ($gym->isDirty('logo_path') && $gym->getOriginal('logo_path')) {
            Storage::disk('public')->delete($gym->getOriginal('logo_path'));
        }
    }

    public function forceDeleted(Gym $gym): void
    {
        if ($gym->logo_path) {
            Storage::disk('public')->delete($gym->logo_path);
        }
    }
}
