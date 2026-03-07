<?php

namespace App\Actions\Auth;

use App\Actions\User\UpdateProfilePhoto;
use App\Models\User;

class CompleteOnboarding
{
    /**
     * Completes user onboarding and assigns crucial initial data.
     *
     * @param User $user
     * @param array $data Contains phone, gender, dob, and level.
     * @return void
     */
    public function execute(User $user, array $data, UpdateProfilePhoto $updatePhotoAction): void
    {
        if (isset($data['photo']) && $data['photo']) {
            $updatePhotoAction->execute($user, $data['photo']);
        }

        $user->update([
            'phone' => $data['phone'],
            'gender' => (int) $data['gender'],
            'dob' => $data['dob'],
            'level' => (int) $data['level'],
            'is_onboarded' => true
        ]);
    }
}
