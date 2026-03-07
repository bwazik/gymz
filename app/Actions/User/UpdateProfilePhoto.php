<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateProfilePhoto
{
    /**
     * Updates the user's profile photo and deletes the old one if it exists.
     *
     * @param User $user
     * @param UploadedFile|null $photo
     * @return void
     */
    public function execute(User $user, ?UploadedFile $photo): void
    {
        if (!$photo) {
            return;
        }

        // Delete old photo if it exists and isn't a default Google/placeholder URL
        if ($user->image_path && !str_starts_with($user->image_path, 'http')) {
            Storage::disk('public')->delete($user->image_path);
        }

        // Store new photo
        $path = $photo->store('avatars', 'public');
        $user->image_path = $path;

        // Save the updated path explicitly if not saving the whole model right away
        // However, in our controllers, we often call $user->save() shortly after.
        // For Single Responsibility, the action updates the DB to ensure consistency.
        $user->save();
    }
}
