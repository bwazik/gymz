<div x-data="{
    show: false,
    message: '',
    type: 'success',
    timeout: null,
    toast(data) {
        this.message = data.message || '';
        this.type = data.type || 'success';
        this.show = true;
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => this.show = false, 3000);
    }
}" @toast.window="toast($event.detail)"
    class="fixed top-[calc(1rem+env(safe-area-inset-top))] left-1/2 -translate-x-1/2 z-[100]">
    <div x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        class="flex items-center gap-2.5 bg-white/90 dark:bg-[#2c2c2e]/90 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-full px-5 py-3 shadow-2xl"
        style="display: none;">
        {{-- Success Icon --}}
        <template x-if="type === 'success'">
            <div class="w-6 h-6 rounded-full bg-green-500/15 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-3.5 h-3.5 text-green-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
        </template>

        {{-- Error Icon --}}
        <template x-if="type === 'error'">
            <div class="w-6 h-6 rounded-full bg-red-500/15 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-3.5 h-3.5 text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </template>

        <span class="text-sm font-bold whitespace-nowrap"
            :class="type === 'error' ? 'text-red-500 dark:text-red-400' : 'text-gray-900 dark:text-white'"
            x-text="message">
        </span>
    </div>
</div>
