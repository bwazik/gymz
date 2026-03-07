<?php

namespace App\Livewire\Profile;

use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UpdatePassword extends Component
{
    use WithToast, WithRateLimiting;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function messages(): array
    {
        return [
            'current_password.required' => 'لازم تكتب كلمة السر الحالية',
            'current_password.current_password' => 'كلمة السر الحالية غلط',
            'password.required' => 'لازم تكتب كلمة السر الجديدة',
            'password.min' => 'كلمة السر لازم تكون 8 حروف على الأقل',
            'password.confirmed' => 'تأكيد كلمة السر مش متطابق',
        ];
    }

    public function updatePassword(): void
    {
        if ($this->isRateLimited('update-password', 3)) {
            return;
        }

        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', 'max:255', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            $this->toastError(collect($e->errors())->flatten()->first());
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->toastSuccess('تم تغيير كلمة السر بنجاح! 🔒');
    }

    public function render()
    {
        return view('livewire.profile.update-password');
    }
}
