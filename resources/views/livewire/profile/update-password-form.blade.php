<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
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
        <div
            class="bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-3xl overflow-hidden shadow-sm">

            {{-- Current Password --}}
            <div class="relative border-b border-black/5 dark:border-white/10 flex items-center px-4">
                <span class="text-gray-400 dark:text-white/40 w-24 text-sm font-medium">الحالية</span>
                <input wire:model="current_password" id="current_password" type="password"
                    class="flex-1 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-4 text-sm font-bold placeholder-gray-400 text-left [&:-webkit-autofill]:[transition:background-color_9999999s_ease-in-out_0s] [&:-webkit-autofill]:[-webkit-text-fill-color:inherit] dark:[&:-webkit-autofill]:[-webkit-text-fill-color:#fff]"
                    dir="ltr" required autocomplete="current-password">
            </div>

            {{-- New Password --}}
            <div class="relative border-b border-black/5 dark:border-white/10 flex items-center px-4">
                <span class="text-gray-400 dark:text-white/40 w-24 text-sm font-medium">الجديدة</span>
                <input wire:model="password" id="password" type="password"
                    class="flex-1 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-4 text-sm font-bold placeholder-gray-400 text-left [&:-webkit-autofill]:[transition:background-color_9999999s_ease-in-out_0s] [&:-webkit-autofill]:[-webkit-text-fill-color:inherit] dark:[&:-webkit-autofill]:[-webkit-text-fill-color:#fff]"
                    dir="ltr" required autocomplete="new-password">
            </div>

            {{-- Confirm Password --}}
            <div class="relative flex items-center px-4">
                <span class="text-gray-400 dark:text-white/40 w-24 text-sm font-medium">تأكيد</span>
                <input wire:model="password_confirmation" id="password_confirmation" type="password"
                    class="flex-1 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-4 text-sm font-bold placeholder-gray-400 text-left [&:-webkit-autofill]:[transition:background-color_9999999s_ease-in-out_0s] [&:-webkit-autofill]:[-webkit-text-fill-color:inherit] dark:[&:-webkit-autofill]:[-webkit-text-fill-color:#fff]"
                    dir="ltr" required autocomplete="new-password">
            </div>

        </div>



        <button type="submit" wire:loading.attr="disabled"
            class="w-full mt-4 py-3.5 rounded-2xl bg-gymz-accent text-white font-bold active:scale-95 transition-all shadow-lg shadow-gymz-accent/20 disabled:opacity-50">
            <span wire:loading.remove wire:target="updatePassword">تغيير كلمة السر</span>
            <span wire:loading wire:target="updatePassword">جاري الحفظ...</span>
        </button>
    </form>
</section>
