<div>
    {{-- Section Title --}}
    <div class="mb-5 mt-4" id="tour-feed">
        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 tracking-wider">
            {{ now()->locale('ar')->translatedFormat('l، j F') }}
        </span>
        <div class="flex items-center justify-between mt-0.5">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">تمارين النهاردة</h2>
            <span class="text-[11px] font-semibold text-gymz-accent bg-gymz-accent/10 px-2.5 py-1 rounded-full">٢٤
                ساعة</span>
        </div>
    </div>

    {{-- Filter Trigger Button --}}
    @php
        $activeFiltersCount = collect([$gymFilter, $categoryFilter, $targetFilter])
            ->filter()
            ->count();
    @endphp
    <div class="mb-5 flex justify-end">
        <button wire:click="$set('showFiltersModal', true)"
            class="relative flex items-center gap-2 px-4 py-2 bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-xl border border-black/5 dark:border-white/10 rounded-2xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 active:scale-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4 text-gymz-accent">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            فلترة التمارين
            @if ($activeFiltersCount > 0)
                <span
                    class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white shadow-sm ring-2 ring-white dark:ring-[#1c1c1e]">
                    {{ $activeFiltersCount }}
                </span>
            @endif
        </button>
    </div>

    @forelse ($intents as $intent)
        @include('partials.intent-card', ['intent' => $intent, 'sentRequestIntentIds' => $sentRequestIntentIds])
    @empty
        {{-- Empty State --}}
        <x-glass-card class="p-8 text-center" x-data>
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-white/30">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                </svg>
            </div>
            <h3 class="text-gray-700 dark:text-white/70 font-medium mb-1">مفيش تمارين بالمواصفات دي دلوقتي</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">خليك إنت المبادر ونزل تمرينة! 💪</p>
        </x-glass-card>
    @endforelse

    <livewire:create-intent />

    {{-- Filters Bottom Sheet Modal --}}
    <div x-data="{ open: @entangle('showFiltersModal').live }" x-show="open" x-init="$watch('open', val => $dispatch(val ? 'hide-bottom-nav' : 'show-bottom-nav'))" style="display: none;"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/40 backdrop-blur-md flex items-end justify-center"
        @click.self="open = false">

        {{-- Bottom Sheet Body --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-out duration-300" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 p-6 rounded-t-[2rem] w-full max-w-md shadow-2xl pb-[calc(1.5rem+env(safe-area-inset-bottom))] flex flex-col max-h-[85vh]"
            @click.stop>

            {{-- Drag Handle --}}
            <div class="flex justify-center mb-4 shrink-0">
                <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5 shrink-0">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">تخصيص الفلاتر</h3>
                <button type="button" wire:click="resetFilters"
                    class="text-xs font-bold text-red-500 hover:text-red-400 active:scale-95 transition-all">مسح
                    الكل</button>
            </div>

            {{-- Filter Content --}}
            <div class="overflow-y-auto space-y-6 pb-4">
                {{-- Categories --}}
                <div>
                    <h4 class="text-[11px] font-bold text-gray-400 mb-2.5 uppercase tracking-wider">نوع التمرين</h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="$set('categoryFilter', null)"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $categoryFilter === null ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-600 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                            الكل
                        </button>
                        @foreach ($categories as $category)
                            <button type="button" wire:click="$set('categoryFilter', {{ $category->id }})"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $categoryFilter === $category->id ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-600 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Gyms --}}
                <div>
                    <h4 class="text-[11px] font-bold text-gray-400 mb-2.5 uppercase tracking-wider">الجيمات</h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="$set('gymFilter', null)"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $gymFilter === null ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-600 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                            الكل
                        </button>
                        @foreach ($gyms as $gym)
                            <button type="button" wire:click="$set('gymFilter', {{ $gym->id }})"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $gymFilter === $gym->id ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-600 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                                {{ $gym->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Targets --}}
                <div>
                    <h4 class="text-[11px] font-bold text-gray-400 mb-2.5 uppercase tracking-wider">الهدف العضلي
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="$set('targetFilter', null)"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $targetFilter === null ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-600 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                            الكل
                        </button>
                        @foreach ($targets as $target)
                            {{-- Hide target if Category is selected and target doesn't belong to it --}}
                            @if (!$categoryFilter || $target->workout_category_id === $categoryFilter)
                                <button type="button" wire:click="$set('targetFilter', {{ $target->id }})"
                                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all border {{ $targetFilter === $target->id ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-600 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                                    {{ $target->name }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="mt-4 pt-2 shrink-0">
                <x-ios-button @click="open = false" type="button">
                    عرض النتائج
                </x-ios-button>
            </div>
        </div>
    </div>
</div>
