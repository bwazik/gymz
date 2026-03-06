<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
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

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        $key = 'update-password:' . Auth::id();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "محاولات كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }

        RateLimiter::hit($key, 60);

        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', 'max:255', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('toast', message: 'تم تغيير كلمة السر بنجاح! 🔒', type: 'success');
    }
}; ?>

<section>
    <header class="mb-4 text-center">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
            كلمة السر
        </h2>
    </header>

    <form wire:submit="updatePassword" class="space-y-4">
        {{-- Glass Container for Fields --}}
        <x-ios-input-group>

            {{-- Current Password --}}
            <x-ios-input label="الحالية" id="current_password" type="password" wire:model="current_password" dir="ltr" labelWidth="w-24" required maxlength="255" autocomplete="current-password" />

            {{-- New Password --}}
            <x-ios-input label="الجديدة" id="password" type="password" wire:model="password" dir="ltr" labelWidth="w-24" required minlength="8" maxlength="255" autocomplete="new-password" />

            {{-- Confirm Password --}}
            <x-ios-input label="تأكيد" id="password_confirmation" type="password" wire:model="password_confirmation" dir="ltr" labelWidth="w-24" required minlength="8" maxlength="255" autocomplete="new-password" />

        </x-ios-input-group>



        <x-ios-button target="updatePassword" wire:loading.attr="disabled">تغيير كلمة السر</x-ios-button>
    </form>
</section>
