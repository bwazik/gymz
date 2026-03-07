@props(['historyItems'])

{{-- History Modal Overlay --}}
<div x-data="{ open: @entangle('showHistoryModal').live }" x-show="open" x-init="$watch('open', val => $dispatch(val ? 'hide-bottom-nav' : 'show-bottom-nav'))" style="display: none;"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 bg-black/40 backdrop-blur-md flex items-end justify-center" @click.self="open = false">

    {{-- Bottom Sheet Modal --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-out duration-300" x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 p-6 rounded-t-[2rem] w-full max-w-md shadow-2xl pb-[calc(1.5rem+env(safe-area-inset-bottom))] max-h-[85vh] flex flex-col"
        @click.stop>

        {{-- Drag Handle --}}
        <div class="flex justify-center mb-4 shrink-0">
            <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
        </div>

        {{-- Title & Close Button --}}
        <div class="flex items-center justify-between mb-5 shrink-0">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">سجل التمارين</h3>
            <button @click="open = false" type="button"
                class="p-2 bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-white/70 rounded-full active:scale-95 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- History List --}}
        <div class="overflow-y-auto space-y-3 pb-4">
            @forelse ($historyItems as $item)
                @php
                    $badgeText = 'غير معروف';
                    $badgeColors = 'bg-gray-200 dark:bg-white/10 text-gray-600 dark:text-gray-300';
                    $icon =
                        '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';

                    if ($item->badge_status === 'no_show_host') {
                        $badgeText = 'محدش انضم';
                    } elseif ($item->badge_status === \App\Enums\SessionStatus::Completed) {
                        $badgeText = 'تمت';
                        $badgeColors = 'bg-green-500/10 text-green-500';
                        $icon =
                            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>';
                    } elseif ($item->badge_status === \App\Enums\SessionStatus::Missed) {
                        $badgeText = 'فائتة ❌';
                        $badgeColors = 'bg-red-500/10 text-red-500';
                    } elseif ($item->badge_status === \App\Enums\SessionStatus::Cancelled_By_Host) {
                        $badgeText = 'إلغاء الكابتن';
                        $badgeColors = 'bg-red-500/10 text-red-500';
                    } elseif ($item->badge_status === \App\Enums\SessionStatus::Cancelled_By_Guest) {
                        $badgeText = 'إلغاء الضيف';
                    }
                @endphp
                <x-glass-card class="flex items-center justify-between opacity-80 !mb-0 !p-4">
                    <div class="flex flex-col">
                        <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $item->target_name }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $item->gym_name }} •
                            {{ \Carbon\Carbon::parse($item->start_time)->translatedFormat('j F Y - g:i A') }}
                        </span>
                    </div>
                    <span
                        class="text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1 shrink-0 {{ $badgeColors }}">
                        {!! $icon !!} {{ $badgeText }}
                    </span>
                </x-glass-card>
            @empty
                <div class="text-center py-6 text-sm text-gray-500 dark:text-gray-400">لسه معندكش تاريخ رياضي.. ابدأ أول
                    تمرينة ليك! 🏋️‍♂️</div>
            @endforelse
        </div>
    </div>
</div>
