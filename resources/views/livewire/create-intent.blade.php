<div>
    {{-- Floating Action Button --}}
    <button wire:click="$set('showModal', true)"
        class="fixed bottom-[calc(7.5rem+env(safe-area-inset-bottom))] right-6 z-40 w-14 h-14 rounded-full bg-gymz-accent text-white flex items-center justify-center shadow-[0_8px_30px_rgba(255,45,85,0.4)] hover:scale-105 active:scale-95 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="w-7 h-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    {{-- Modal Overlay --}}
    @if ($showModal)
        <div x-data="{ open: true }" x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/40 backdrop-blur-md flex items-end justify-center transition-opacity"
            wire:click.self="$set('showModal', false)">
            {{-- Bottom Sheet Modal --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 p-6 rounded-t-[2rem] w-full max-w-md shadow-2xl pb-[calc(1.5rem+env(safe-area-inset-bottom))]"
                @click.stop>
                {{-- Drag Handle --}}
                <div class="flex justify-center mb-4">
                    <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">إضافة تمرينة جديدة</h3>

                {{-- Flash Message --}}
                @if (session()->has('message'))
                    <div class="mb-4 px-4 py-2 rounded-2xl bg-gymz-accent/15 text-gymz-accent text-sm font-medium">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit="save" class="space-y-4">
                    {{-- Gym Select --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">الجيم</label>
                        <x-ios-select wire:model="gym_id" :options="$gyms->pluck('name', 'id')" placeholder="اختار الجيم..." />
                        @error('gym_id')
                            <span
                                class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Category Select --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">نوع
                            التمرين</label>
                        <x-ios-select wire:model.live="workout_category_id" :options="$categories->pluck('name', 'id')" placeholder="اختار النوع..." />
                        @error('workout_category_id')
                            <span
                                class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Target Select (dynamic) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">العضلة /
                            الهدف</label>
                        <x-ios-select wire:model="workout_target_id" :options="$targets->pluck('name', 'id')" placeholder="{{ $targets->isEmpty() ? 'اختار النوع الأول...' : 'اختار الهدف...' }}" />
                        @error('workout_target_id')
                            <span
                                class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Start Time --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">ميعاد
                            التمرين</label>
                        <div class="relative">
                            <input type="datetime-local" wire:model="start_time"
                                class="w-full rounded-2xl bg-black/5 dark:bg-white/10 border-0 text-gray-900 dark:text-white text-sm px-4 py-3.5 focus:bg-white dark:focus:bg-[#3a3a3c] focus:ring-2 focus:ring-gymz-accent/50 transition-all dark:[color-scheme:dark]">
                        </div>
                        @error('start_time')
                            <span
                                class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Guest Pass Toggle --}}
                    <div class="flex items-center justify-between py-1">
                        <div>
                            <label class="text-sm font-medium text-gray-900 dark:text-white">معايا دعوة (Guest
                                Pass)</label>
                            <p class="text-xs text-gray-500 dark:text-white/40">تقدر تدخل حد معندوش اشتراك في الجيم</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" dir="ltr">
                            <input type="checkbox" wire:model="has_invitation" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-300 dark:bg-white/10 rounded-full peer peer-checked:bg-gymz-accent peer-focus:ring-2 peer-focus:ring-gymz-accent/30 transition-colors after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all after:shadow-sm peer-checked:after:translate-x-full">
                            </div>
                        </label>
                    </div>

                    {{-- Note --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">ملاحظات <span
                                class="text-gray-400 dark:text-white/30">(اختياري)</span></label>
                        <textarea wire:model="note" rows="2" placeholder="محتاج spotter، عايز أكسر PR النهاردة..."
                            class="w-full rounded-2xl bg-black/5 dark:bg-white/10 border-0 text-gray-900 dark:text-white text-sm px-4 py-3 focus:bg-white dark:focus:bg-[#3a3a3c] focus:ring-2 focus:ring-gymz-accent/50 transition-all resize-none placeholder-gray-400 dark:placeholder-white/30"></textarea>
                        @error('note')
                            <span
                                class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 py-3.5 rounded-2xl bg-gymz-accent text-white font-bold text-sm active:scale-95 transition-all shadow-md disabled:opacity-50">
                            <span wire:loading.remove wire:target="save">انشر التمرينة 🔥</span>
                            <span wire:loading wire:target="save">جاري النشر...</span>
                        </button>
                        <button type="button" wire:click="$set('showModal', false)"
                            class="px-6 py-3.5 rounded-2xl bg-gray-200/50 dark:bg-white/10 text-gray-700 dark:text-white/70 font-bold text-sm active:scale-95 transition-all">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
