<div x-data x-init="$dispatch('hide-bottom-nav')" class="flex flex-col items-center justify-center min-h-[70vh]">
  <div class="w-full max-w-md text-center mb-8">
    <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">أهلاً بيك يا {{ explode(' ', auth()->user()->name)[0] }}! 🚀</h1>
    <p class="text-gray-500 dark:text-gray-400">خطوة واحدة وتبقى جاهز تنزل الملعب.</p>
  </div>
  <form wire:submit="save" class="w-full max-w-md space-y-6">
    <x-ios-input-group>
      <div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex flex-col justify-center px-4 py-2 z-40">
        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1">الموبايل</label>
        <x-ios-input id="phone" type="tel" wire:model="phone" dir="ltr" placeholder="01xxxxxxxxx" required />
        @error('phone') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
      </div>
      <div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex flex-col justify-center px-4 py-2 z-30">
        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1">النوع</label>
        <x-ios-select wire:model="gender" :options="[\App\Enums\Gender::Male->value => 'ولد 👨', \App\Enums\Gender::Female->value => 'بنت 👩']" placeholder="اختار النوع..." required />
        @error('gender') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
      </div>
      <div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex flex-col justify-center px-4 py-2 z-20">
        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1">مستواك في الجيم</label>
        <x-ios-select wire:model="level" :options="[\App\Enums\UserLevel::Beginner->value => 'لسه ببدأ 🐣', \App\Enums\UserLevel::Mid->value => 'عاشق للحديد 💪', \App\Enums\UserLevel::Pro->value => 'فورمة الساحل 🔥']" placeholder="حدد مستواك..." required />
        @error('level') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
      </div>
      <div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex flex-col justify-center px-4 py-2 z-10">
        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1">تاريخ الميلاد</label>
        <x-ios-input id="dob" wire:model="dob" type="date" required />
        @error('dob') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
      </div>
    </x-ios-input-group>
    <x-ios-button target="save">يلا نبدأ التمرين 🔥</x-ios-button>
  </form>
</div>