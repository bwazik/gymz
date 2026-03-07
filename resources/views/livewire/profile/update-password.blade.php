<section>
    <header class="mb-4 text-center">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">كلمة السر</h2>
    </header>

    <form wire:submit="updatePassword" class="space-y-4">
        {{-- Glass Container for Fields --}}
        <x-ios-input-group>
            {{-- Current Password --}}
            <x-ios-input label="الحالية" id="current_password" type="password" wire:model="current_password" dir="ltr"
                labelWidth="w-24" required maxlength="255" autocomplete="current-password" />

            {{-- New Password --}}
            <x-ios-input label="الجديدة" id="password" type="password" wire:model="password" dir="ltr"
                labelWidth="w-24" required minlength="8" maxlength="255" autocomplete="new-password" />

            {{-- Confirm Password --}}
            <x-ios-input label="تأكيد" id="password_confirmation" type="password" wire:model="password_confirmation"
                dir="ltr" labelWidth="w-24" required minlength="8" maxlength="255" autocomplete="new-password" />
        </x-ios-input-group>

        <x-ios-button target="updatePassword" wire:loading.attr="disabled">تغيير كلمة السر</x-ios-button>
    </form>
</section>
