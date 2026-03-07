@props(['title', 'description'])

<div x-data="{ clicked: false }"
    @click="clicked = true; setTimeout(() => clicked = false, 300); $dispatch('toast', { message: 'متجر الجلوتس هينزل قريب! كمل تمرين وجمع جلوتس 🔥', type: 'success' })"
    :class="clicked ? 'scale-95' : 'scale-100'"
    class="flex items-center gap-4 p-4 rounded-2xl bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5 cursor-pointer transition-all duration-200 hover:bg-black/10 dark:hover:bg-white/10 group">

    {{-- Blur Preview Box --}}
    <div
        class="relative w-16 h-16 rounded-xl bg-gray-200 dark:bg-[#2c2c2e] overflow-hidden flex items-center justify-center shrink-0">
        <div
            class="absolute inset-0 bg-gradient-to-br from-white/40 to-black/10 dark:from-white/10 dark:to-black/30 backdrop-blur-xl">
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
            class="w-6 h-6 text-gray-400 dark:text-gray-500 z-10">
            <path fill-rule="evenodd"
                d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                clip-rule="evenodd" />
        </svg>
    </div>

    {{-- Details --}}
    <div class="flex-1">
        <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-gymz-accent transition-colors">
            {{ $title }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ $description }}</p>
    </div>

    {{-- Mystery Price --}}
    <div class="shrink-0 text-left">
        <span
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/5 dark:bg-white/10 text-xs font-bold text-gray-600 dark:text-gray-300">
            <span>???</span>
            <img src="{{ asset('images/peach.svg') }}" class="w-3 h-3 grayscale opacity-50" alt="glutes">
        </span>
    </div>
</div>
