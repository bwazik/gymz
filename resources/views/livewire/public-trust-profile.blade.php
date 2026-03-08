<div>
    {{-- Modal Overlay --}}
    <div x-data="{ open: @entangle('showModal').live }" x-show="open" x-init="$watch('open', val => { if (!val) setTimeout(() => $wire.set('targetUser', null), 300);
        $dispatch(val ? 'hide-bottom-nav' : 'show-bottom-nav') })" style="display: none;"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[60] bg-black/40 backdrop-blur-md flex items-end justify-center"
        @click.self="open = false">

        {{-- Bottom Sheet Modal --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-out duration-300" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 rounded-t-[2rem] w-full max-w-md shadow-2xl relative"
            @click.stop>

            {{-- Drag Handle --}}
            <div class="flex justify-center pt-4 pb-2">
                <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
            </div>

            @if ($targetUser)
                <div class="p-6 pt-2 pb-[calc(1.5rem+env(safe-area-inset-bottom))]">

                    {{-- Header: Avatar & Info --}}
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-3">
                            <img src="{{ $targetUser->image_path ? (Str::startsWith($targetUser->image_path, 'http') ? $targetUser->image_path : Storage::url($targetUser->image_path)) : asset('images/default.jpg') }}"
                                referrerpolicy="no-referrer" alt="{{ $targetUser->name }}"
                                class="w-24 h-24 rounded-full object-cover border-4 border-white/50 dark:border-[#1c1c1e]/50 shadow-lg">

                            {{-- Quick Trust Shield --}}
                            <div
                                class="absolute -bottom-1 -right-1 bg-gymz-accent text-white rounded-full p-1.5 shadow-md border-2 border-white dark:border-[#1c1c1e]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                        d="M12.515 2.276a1.5 1.5 0 0 0-1.03 0 11.812 11.812 0 0 0-6.22 4.414A.75.75 0 0 0 5 7.15c0 5.483 3.037 10.38 7.5 13.064a1.5 1.5 0 0 0 1.5 0C18.463 17.53 21.5 12.632 21.5 7.15a.75.75 0 0 0-.265-.46A11.81 11.81 0 0 0 15 2.276Zm-1.896 9.544a.75.75 0 0 0-1.157.94l2.5 3a.75.75 0 0 0 1.152.008l4-4.5a.75.75 0 1 0-1.124-1.002l-3.415 3.842-1.956-2.288Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $targetUser->name }}</h3>
                        <p class="text-sm font-semibold text-gymz-accent bg-gymz-accent/10 px-3 py-1 rounded-full mt-2">
                            {{ $targetUser->level?->getLabel() ?? 'عضو جديد' }}
                        </p>
                    </div>

                    {{-- Metrics Grid --}}
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        {{-- Completed Sessions Badge --}}
                        <div
                            class="bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                            <span
                                class="text-3xl font-black text-gray-900 dark:text-white">{{ $completedSessionsCount }}</span>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">تمرينة مكتملة</span>
                        </div>

                        {{-- Reliability Score --}}
                        @php
                            $score = $targetUser->reliability_score;
                            $scoreColor = 'text-green-500';
                            $scoreBg = 'bg-green-500/10';
                            if ($score < 50) {
                                $scoreColor = 'text-red-500';
                                $scoreBg = 'bg-red-500/10';
                            } elseif ($score < 80) {
                                $scoreColor = 'text-amber-500';
                                $scoreBg = 'bg-amber-500/10';
                            }
                        @endphp
                        <div
                            class="bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                            <div class="flex items-baseline gap-1 {{ $scoreColor }}">
                                <span class="text-3xl font-black">{{ $score }}</span>
                                <span class="text-sm font-bold">%</span>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">نسبة
                                الموثوقية</span>
                        </div>
                    </div>

                    {{-- Footer Info & Close --}}
                    <div class="flex items-center justify-between border-t border-black/5 dark:border-white/10 pt-4">
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            عضو منذ {{ $targetUser->created_at->translatedFormat('F Y') }}
                        </div>
                        <button type="button" @click="open = false"
                            class="px-5 py-2 rounded-xl bg-gray-200/50 dark:bg-white/10 text-gray-700 dark:text-white/70 font-bold text-sm active:scale-95 transition-all">
                            إغلاق
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
