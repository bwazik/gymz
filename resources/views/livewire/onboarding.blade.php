<div x-data x-init="$dispatch('hide-bottom-nav')" class="min-h-screen flex flex-col items-center justify-center p-6">
    <div class="mb-6">
        <x-photo-upload :user="auth()->user()" :photo="$photo" sizeClasses="w-24 h-24 shadow-xl" />
    </div>

    <div class="w-full max-w-md text-center mb-4">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">أهلاً بيك يا
            {{ explode(' ', auth()->user()->name)[0] }}! 🏋🏽</h1>
        <p class="text-gray-500 dark:text-gray-400">خطوة واحدة وتبقى جاهز تتمرن.</p>
    </div>

    <form wire:submit="save" class="w-full max-w-md space-y-4">
        {{-- Phone --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">الموبايل</label>
            <div class="relative">
                <input id="phone" type="tel" wire:model="phone" dir="ltr" placeholder="01xxxxxxxxx"
                    required
                    class="w-full rounded-2xl bg-black/5 dark:bg-white/10 border-0 text-gray-900 dark:text-white text-sm px-4 py-3.5 focus:bg-white dark:focus:bg-[#3a3a3c] focus:ring-2 focus:ring-gymz-accent/50 transition-all text-left">
            </div>
        </div>

        {{-- Gender --}}
        <div class="relative z-30">
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">النوع</label>
            <x-ios-select wire:model="gender" :options="[\App\Enums\Gender::Male->value => 'ولد', \App\Enums\Gender::Female->value => 'بنت']" placeholder="اختار النوع..." required />
        </div>

        {{-- Level --}}
        <div class="relative z-20">
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">مستواك في الجيم</label>
            <x-ios-select wire:model="level" :options="[
                \App\Enums\UserLevel::Beginner->value => 'قطة (بتمرن بقالي من 4 شهور ل سنة)',
                \App\Enums\UserLevel::Mid->value => 'أسد (بتمرن بقالي من سنة ل 3 سنين)',
                \App\Enums\UserLevel::Pro->value => 'فحل (بتمرن بقالي اكتر من 3 سنين)',
            ]" placeholder="حدد مستواك..." required />
        </div>

        {{-- DOB --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">تاريخ الميلاد</label>
            <div class="relative">
                <input id="dob" type="date" wire:model="dob" required
                    class="w-full rounded-2xl bg-black/5 dark:bg-white/10 border-0 text-gray-900 dark:text-white text-sm px-4 py-3.5 focus:bg-white dark:focus:bg-[#3a3a3c] focus:ring-2 focus:ring-gymz-accent/50 transition-all dark:[color-scheme:dark]">
            </div>
        </div>

        <div class="pt-4">
            <x-ios-button target="save">يلا نبدأ التمرين 🔥</x-ios-button>
        </div>
    </form>
</div>
