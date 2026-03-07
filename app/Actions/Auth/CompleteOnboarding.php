<?php

namespace App\Actions\Auth;

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
    public function execute(User $user, array $data): void
    {
        if (isset($data['photo']) && $data['photo']) {
            $path = $data['photo']->store('avatars', 'public');
            $user->image_path = $path;
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
