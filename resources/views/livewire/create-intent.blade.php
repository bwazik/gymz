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
    <div x-data="{ open: @entangle('showModal').live }" x-show="open" x-init="$watch('open', val => $dispatch(val ? 'hide-bottom-nav' : 'show-bottom-nav'))" style="display: none;"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/40 backdrop-blur-md flex items-end justify-center"
        @click.self="open = false">
        {{-- Bottom Sheet Modal --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-out duration-300" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 p-6 rounded-t-[2rem] w-full max-w-md shadow-2xl pb-[calc(1.5rem+env(safe-area-inset-bottom))]"
            @click.stop>
            {{-- Drag Handle --}}
            <div class="flex justify-center mb-4">
                <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
            </div>

            {{-- Title --}}
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">إضافة تمرينة جديدة</h3>

            <form wire:submit="save" class="space-y-4">
                {{-- Gym Select --}}
                <div class="relative z-30">
                    <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">الجيم</label>
                    <x-ios-select wire:model="form.gym_id" :options="$gyms->pluck('name', 'id')" placeholder="اختار الجيم..." />
                    @error('form.gym_id')
                        <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
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
                <div class="relative z-20">
                    <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">نوع
                        التمرين</label>
                    <x-ios-select wire:model.live="form.workout_category_id" :options="$categories->pluck('name', 'id')"
                        placeholder="اختار النوع..." />
                    @error('form.workout_category_id')
                        <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
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
                <div class="relative z-10">
                    <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">العضلة /
                        الهدف</label>
                    <x-ios-select wire:model="form.workout_target_id" :options="$targets->pluck('name', 'id')"
                        placeholder="{{ $targets->isEmpty() ? 'اختار النوع الأول...' : 'اختار الهدف...' }}"
                        :disabled="$targets->isEmpty()" />
                    @error('form.workout_target_id')
                        <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
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
                        <x-ios-datetime wire:model="form.start_time" />
                    </div>
                    @error('form.start_time')
                        <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Guest Pass Toggle --}}
                <x-ios-toggle label="معايا دعوة (Guest Pass)" description="تقدر تدخل حد معندوش اشتراك في الجيم" wire:model="form.has_invitation" />

                {{-- Note --}}
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">ملاحظات <span
                            class="text-gray-400 dark:text-white/30">(اختياري)</span></label>
                    <x-ios-textarea wire:model="form.note" rows="2" placeholder="محتاج spotter، عايز أكسر PR النهاردة..." />
                    @error('form.note')
                        <span class="flex items-center gap-1 text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
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
                        <span wire:loading.remove wire:target="save">انشر التمرينة</span>
                        <span wire:loading wire:target="save">جاري النشر...</span>
                    </button>
                    <button type="button" @click="open = false"
                        class="px-6 py-3.5 rounded-2xl bg-gray-200/50 dark:bg-white/10 text-gray-700 dark:text-white/70 font-bold text-sm active:scale-95 transition-all">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
