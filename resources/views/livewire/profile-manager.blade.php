<div>
    {{-- Main Profile Stats --}}
    <x-profile.stats :user="$user" :photo="$photo" />

    {{-- Active Workouts --}}
    <x-profile.active-workouts :active-intents="$activeIntents" />

    {{-- Reusable Update Info Form --}}
    <header class="mb-4 mt-8 text-center">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
            المعلومات الأساسية
        </h2>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-4">
        {{-- Glass Container for Fields --}}
        <x-ios-input-group>

            {{-- Name --}}
            <x-ios-input label="الاسم" id="name" wire:model="name" placeholder="اسمك" required autofocus
                maxlength="255" />

            {{-- Email --}}
            <x-ios-input label="الإيميل" id="email" type="email" wire:model="email" dir="ltr"
                placeholder="email@example.com" required maxlength="255" />

            {{-- Phone --}}
            <x-ios-input label="الموبايل" id="phone" type="tel" wire:model="phone" dir="ltr"
                placeholder="01xxxxxxxxx" maxlength="11" pattern="^(011|010|012|015)\d{8}$" />

        </x-ios-input-group>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="px-4">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    لم يتم التحقق من الإيميل.
                    <button wire:click.prevent="sendVerification" class="underline text-sm text-gymz-accent">
                        اضغط هنا لإعادة إرسال رابط التحقق.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        تم إرسال رابط جديد.
                    </p>
                @endif
            </div>
        @endif

        <x-ios-button target="updateProfileInformation" wire:loading.attr="disabled">حفظ البيانات</x-ios-button>
    </form>

    {{-- History Modal --}}
    <x-profile.history-modal :history-items="$this->historyItems" />
</div>
