<div x-data x-init="$dispatch('hide-bottom-nav')" class="min-h-screen flex flex-col items-center justify-center p-6 bg-[#F2F2F7] dark:bg-black">
    <div class="w-full max-w-md text-center mb-8">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">أهلاً بيك يا {{ explode(' ', auth()->user()->name)[0] }}! 🚀</h1>
        <p class="text-gray-500 dark:text-gray-400">خطوة واحدة وتبقى جاهز تنزل الملعب.</p>
    </div>
    
    <form wire:submit="save" class="w-full max-w-md space-y-4">
        {{-- Phone --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">الموبايل</label>
            <div class="relative">
                <input id="phone" type="tel" wire:model="phone" dir="ltr" placeholder="01xxxxxxxxx" required
                    class="w-full rounded-2xl bg-black/5 dark:bg-white/10 border-0 text-gray-900 dark:text-white text-sm px-4 py-3.5 focus:bg-white dark:focus:bg-[#3a3a3c] focus:ring-2 focus:ring-gymz-accent/50 transition-all text-left">
            </div>
            @error('phone')
                <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- Gender --}}
        <div class="relative z-30">
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">النوع</label>
            <x-ios-select wire:model="gender" :options="[\App\Enums\Gender::Male->value => 'ولد 👨', \App\Enums\Gender::Female->value => 'بنت 👩']" placeholder="اختار النوع..." required />
            @error('gender')
                <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- Level --}}
        <div class="relative z-20">
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">مستواك في الجيم</label>
            <x-ios-select wire:model="level" :options="[
                \App\Enums\UserLevel::Beginner->value => 'لسه ببدأ 🐣',
                \App\Enums\UserLevel::Mid->value => 'عاشق للحديد 💪',
                \App\Enums\UserLevel::Pro->value => 'فورمة الساحل 🔥',
            ]" placeholder="حدد مستواك..." required />
            @error('level')
                <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        {{-- DOB --}}
        <div>
            <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">تاريخ الميلاد</label>
            <div class="relative">
                <input id="dob" type="date" wire:model="dob" required
                    class="w-full rounded-2xl bg-black/5 dark:bg-white/10 border-0 text-gray-900 dark:text-white text-sm px-4 py-3.5 focus:bg-white dark:focus:bg-[#3a3a3c] focus:ring-2 focus:ring-gymz-accent/50 transition-all dark:[color-scheme:dark]">
            </div>
            @error('dob')
                <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="pt-4">
            <x-ios-button target="save">يلا نبدأ التمرين 🔥</x-ios-button>
        </div>
    </form>
</div>