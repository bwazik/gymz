<div class="min-h-screen flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-black">
    <div class="w-full max-w-md text-center mb-8">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">أهلاً بيك يا
            {{ explode(' ', auth()->user()->name)[0] }}! 🚀</h1>
        <p class="text-gray-500 dark:text-gray-400">خطوة واحدة وتبقى جاهز تنزل الملعب.</p>
    </div>

    <form wire:submit="save" class="w-full max-w-md space-y-6">
        <x-ios-input-group>
            <x-ios-input label="الموبايل" type="tel" wire:model="phone" dir="ltr" placeholder="01xxxxxxxxx"
                required />
            <x-ios-select label="النوع" wire:model="gender" :options="['male' => 'ولد 👨', 'female' => 'بنت 👩']" placeholder="اختار النوع..." required />
            <x-ios-select label="مستواك في الجيم" wire:model="level" :options="[
                \App\Enums\UserLevel::Beginner->value => 'لسه ببدأ 🐣',
                \App\Enums\UserLevel::Mid->value => 'عاشق للحديد 💪',
                \App\Enums\UserLevel::Pro->value => 'فورمة الساحل 🔥',
            ]" placeholder="حدد مستواك..."
                required />
            <x-ios-input label="تاريخ الميلاد" wire:model="dob" type="date" required />
        </x-ios-input-group>

        <x-ios-button target="save">يلا نبدأ التمرين 🔥</x-ios-button>
    </form>
</div>
