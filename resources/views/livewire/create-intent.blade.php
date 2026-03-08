<div>
    {{-- Floating Action Button --}}
    <button wire:click="$set('showModal', true)" id="tour-create-btn"
        class="fixed bottom-[calc(7.5rem+env(safe-area-inset-bottom))] right-6 z-40 w-14 h-14 rounded-full bg-gymz-accent text-white flex items-center justify-center shadow-[0_8px_30px_rgba(255,45,85,0.4)] hover:scale-105 active:scale-95 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="w-7 h-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    {{-- Modal Overlay --}}
    <div x-data="{ open: @entangle('showModal').live }" x-show="open" x-init="$watch('open', val => {
        if (!val) setTimeout(() => $wire.set('showShareScreen', false), 300);
        $dispatch(val ? 'hide-bottom-nav' : 'show-bottom-nav')
    })" style="display: none;"
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

            @if (!$showShareScreen)
                {{-- Title --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">إضافة تمرينة جديدة</h3>

                <form wire:submit="save" class="space-y-4">
                    {{-- Gym Select --}}
                    <div class="relative z-30">
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">الجيم</label>
                        <x-ios-select wire:model="form.gym_id" :options="$gyms->pluck('name', 'id')" placeholder="اختار الجيم..." />
                    </div>

                    {{-- Category Select --}}
                    <div class="relative z-20">
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">نوع
                            التمرين</label>
                        <x-ios-select wire:model.live="form.workout_category_id" :options="$categories->pluck('name', 'id')"
                            placeholder="اختار النوع..." />
                    </div>

                    {{-- Target Select (dynamic) --}}
                    <div class="relative z-10">
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">العضلة /
                            الهدف</label>
                        <x-ios-select wire:model="form.workout_target_id" :options="$targets->pluck('name', 'id')"
                            placeholder="{{ $targets->isEmpty() ? 'اختار النوع الأول...' : 'اختار الهدف...' }}"
                            :disabled="$targets->isEmpty()" />
                    </div>

                    {{-- Start Time --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">ميعاد
                            التمرين</label>
                        <div class="relative">
                            <x-ios-datetime wire:model="form.start_time" />
                        </div>
                    </div>

                    {{-- Guest Pass Toggle --}}
                    <x-ios-toggle label="معايا دعوة (Guest Pass)" description="تقدر تدخل حد معندوش اشتراك في الجيم"
                        wire:model="form.has_invitation" />

                    {{-- Note --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">ملاحظات <span
                                class="text-gray-400 dark:text-white/30">(اختياري)</span></label>
                        <x-ios-textarea wire:model="form.note" rows="2"
                            placeholder="محتاج spotter، عايز أكسر PR النهاردة..." />
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 py-3.5 rounded-2xl bg-gymz-accent text-white font-bold text-sm active:scale-95 transition-all shadow-md disabled:opacity-50">
                            <span wire:loading.remove wire:target="save">انشر التمرينة</span>
                            <span wire:loading wire:target="save">اتقل...</span>
                        </button>
                        <button type="button" @click="open = false"
                            class="px-6 py-3.5 rounded-2xl bg-gray-200/50 dark:bg-white/10 text-gray-700 dark:text-white/70 font-bold text-sm active:scale-95 transition-all">
                            إلغاء
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <div
                        class="w-20 h-20 mx-auto mb-5 rounded-[1.5rem] bg-gymz-accent/10 dark:bg-gymz-accent/20 flex items-center justify-center relative">
                        <div
                            class="absolute inset-0 bg-gymz-accent/20 dark:bg-gymz-accent/30 rounded-[1.5rem] filter blur-xl animate-pulse">
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-10 h-10 text-gymz-accent relative z-10">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">عاش يا بطل! 🏋🏽</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">التمرينة اتنشرت والطلبات
                        هتجيلك في التطبيق. ابعت دعوة لأصحابك وخليهم يسجلوا عشان تكسبوا نقط وتتمرنوا سوا!</p>

                    <div class="space-y-3">
                        <a href="https://wa.me/?text={{ urlencode($shareMessage . ' ' . $shareUrl) }}" target="_blank"
                            class="flex items-center justify-center gap-2.5 w-full py-3.5 rounded-2xl bg-[#25D366] text-white font-bold text-sm hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-[#25D366]/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                class="w-5 h-5">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                            </svg>
                            شارك التحدي على واتساب
                        </a>

                        <button type="button"
                            @click="
                                    if (navigator.share) {
                                        navigator.share({ title: 'تحدي GymZ', text: '{{ $shareMessage }}', url: '{{ $shareUrl }}' }).catch(console.error);
                                    } else {
                                        navigator.clipboard.writeText('{{ $shareMessage }} {{ $shareUrl }}');
                                        $dispatch('toast-success', 'تم نسخ الرابط للقصص (Story)!');
                                    }
                                "
                            class="flex items-center justify-center gap-2.5 w-full py-3.5 rounded-2xl bg-gradient-to-tr from-[#f09433] via-[#e6683c] to-[#bc1888] text-white font-bold text-sm hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-[#bc1888]/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                class="w-5 h-5">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm3.98-10.834a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z" />
                            </svg>
                            مشاركة على إنستجرام / أخرى
                        </button>
                        <button type="button"
                            @click="open = false; setTimeout(() => $wire.set('showShareScreen', false), 300)"
                            class="w-full py-3.5 rounded-2xl bg-gray-200/50 dark:bg-white/10 text-gray-700 dark:text-white/70 font-bold text-sm active:scale-95 transition-all">
                            تم النشر
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
