<div x-data x-init="$dispatch('hide-bottom-nav')"
    class="min-h-screen flex flex-col items-center justify-center p-6 bg-[#F2F2F7] dark:bg-black">

    {{-- الهيدر: وسعنا الـ mb عشان الكلام ميبقاش لازق --}}
    <div class="w-full max-w-md text-center mb-10">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">أهلاً بيك يا
            {{ explode(' ', auth()->user()->name)[0] }}! 🚀</h1>
        <p class="text-gray-500 dark:text-gray-400">خطوة واحدة وتبقى جاهز تنزل الملعب.</p>
    </div>

    <form wire:submit="save" class="w-full max-w-md space-y-6">
        {{-- استخدمنا الـ Group مباشرة زي الـ Profile --}}
        <x-ios-input-group>

            {{-- Phone --}}
            <x-ios-input label="الموبايل" id="phone" type="tel" wire:model="phone" dir="ltr" placeholder="01xxxxxxxxx" required labelWidth="w-24" />

            {{-- Gender --}}
            <div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex items-center px-4 py-1 z-30">
                <span class="text-gray-400 dark:text-white/40 text-sm font-medium w-24">النوع</span>
                <x-ios-select wire:model="gender" :options="[\App\Enums\Gender::Male->value => 'ولد 👨', \App\Enums\Gender::Female->value => 'بنت 👩']" placeholder="اختار النوع..." required class="!bg-transparent !px-2 !py-3 !text-sm !font-bold" />
            </div>

            {{-- Level --}}
            <div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex items-center px-4 py-1 z-20">
                <span class="text-gray-400 dark:text-white/40 text-sm font-medium w-24 whitespace-nowrap">مستواك</span>
                <x-ios-select wire:model="level" :options="[
                    \App\Enums\UserLevel::Beginner->value => 'لسه ببدأ 🐣',
                    \App\Enums\UserLevel::Mid->value => 'عاشق للحديد 💪',
                    \App\Enums\UserLevel::Pro->value => 'فورمة الساحل 🔥',
                ]" placeholder="حدد مستواك..." required class="!bg-transparent !px-2 !py-3 !text-sm !font-bold" />
            </div>

            {{-- DOB --}}
            <div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex items-center px-4 py-2 z-10">
                <span class="text-gray-400 dark:text-white/40 text-sm font-medium w-24 whitespace-nowrap">الميلاد</span>
                <x-ios-datetime id="dob" wire:model="dob" required class="!bg-transparent !px-2 !py-3 !text-sm !font-bold w-full" />
            </div>

        </x-ios-input-group>

        <x-ios-button target="save">يلا نبدأ التمرين 🔥</x-ios-button>
    </form>
</div>
