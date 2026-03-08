<div>
    {{-- Section Title --}}
    <div class="mb-5 mt-4">
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
        <button x-data x-on:click.prevent="$dispatch('open-modal', 'feed-filters')"
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
        {{-- Apple Glass Card --}}
        <x-glass-card class="mb-4">

            {{-- Guest Pass Ribbon (Top Left Corner) --}}
            @if ($intent->has_invitation)
                <span
                    class="absolute top-4 left-4 inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                    معايا انفتيشن
                </span>
            @endif

            {{-- Header: Avatar + Name --}}
            <div class="flex items-center gap-3 mb-4">
                @auth
                    <img src="{{ $intent->user->image_path ? (Str::startsWith($intent->user->image_path, 'http') ? $intent->user->image_path : Storage::url($intent->user->image_path)) : asset('images/default.jpg') }}"
                        referrerpolicy="no-referrer" alt="{{ $intent->user->name }}"
                        class="w-11 h-11 rounded-full object-cover border border-black/5 dark:border-white/10">
                    <div>
                        <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $intent->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $intent->user->level?->getLabel() }}</p>
                    </div>
                @endauth
                @guest
                    <div
                        class="w-11 h-11 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center backdrop-blur-sm border border-black/5">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm text-gray-900 dark:text-white filter blur-[2px] select-none">عضو مخفي
                        </p>
                        <p class="text-xs text-gymz-accent">سجل دخول لكشف الهوية</p>
                    </div>
                @endguest
            </div>

            {{-- Body: Gym + Target --}}
            <div class="flex items-center gap-4 mb-4">
                {{-- Gym --}}
                <div class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4 text-gymz-accent">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span>{{ $intent->gym->name }}</span>
                </div>

                {{-- Workout Target --}}
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-gymz-accent/10 text-gymz-accent dark:bg-gymz-accent/15">
                    {{ $intent->workoutTarget->name }}
                </span>
            </div>

            {{-- Footer: Time + Guest Badge + Action --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Time --}}
                    <div class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $intent->start_time->format('g:i A') }}</span>
                        <span class="text-gray-300 dark:text-white/30">·</span>
                        <span>{{ $intent->start_time->diffForHumans() }}</span>
                    </div>

                </div>

                {{-- Action Button --}}
                @auth
                    @if (in_array($intent->id, $sentRequestIntentIds))
                        <span
                            class="px-5 py-2 text-xs font-bold rounded-full bg-black/5 dark:bg-white/10 text-gray-500 dark:text-gray-400 cursor-default">
                            تم الإرسال
                        </span>
                    @else
                        <button wire:click="sendRequest({{ $intent->id }})" wire:loading.attr="disabled"
                            wire:target="sendRequest({{ $intent->id }})"
                            class="px-5 py-2 text-xs font-bold rounded-full bg-gymz-accent text-white active:scale-95 transition-transform duration-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="sendRequest({{ $intent->id }})">ممكن نتمرن؟</span>
                            <span wire:loading wire:target="sendRequest({{ $intent->id }})">بيتبعت...</span>
                        </button>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 text-[11px] font-bold rounded-full bg-gymz-accent text-white active:scale-95 transition-transform duration-200 shadow-lg shadow-gymz-accent/20">
                        سجل واتمرن معاهم 🏋🏽
                    </a>
                @endguest
            </div>
        </x-glass-card>
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

            <button @click="$dispatchTo('create-intent', 'open-modal')"
                class="px-6 py-3 rounded-2xl bg-gymz-accent text-white font-bold text-sm shadow-lg shadow-gymz-accent/20 active:scale-95 transition-all w-full flex justify-center items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                نزل تمرينة
            </button>
        </x-glass-card>
    @endforelse

    <livewire:create-intent />

    {{-- Filters Bottom Sheet Modal --}}
    <x-modal name="feed-filters" maxWidth="md">
        <div
            class="bg-white/90 dark:bg-[#1c1c1e]/90 backdrop-blur-3xl rounded-3xl overflow-hidden border border-black/5 dark:border-white/10">
            {{-- Header --}}
            <div
                class="px-6 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between sticky top-0 bg-white/50 dark:bg-[#1c1c1e]/50 backdrop-blur-md z-10">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg">تخصيص الفلاتر</h3>
                <button type="button" wire:click="resetFilters"
                    class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors">مسح الكل</button>
            </div>

            {{-- Filter Content --}}
            <div class="p-6 space-y-8 max-h-[70vh] overflow-y-auto">
                {{-- Gyms --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">الفرع
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="$set('gymFilter', null)"
                            class="px-4 py-2 rounded-xl text-sm font-bold transition-all border {{ $gymFilter === null ? 'bg-gymz-accent text-white border-transparent' : 'bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                            الكل
                        </button>
                        @foreach ($gyms as $gym)
                            <button type="button" wire:click="$set('gymFilter', {{ $gym->id }})"
                                class="px-4 py-2 rounded-xl text-sm font-bold transition-all border {{ $gymFilter === $gym->id ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                                {{ $gym->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Categories --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">نوع
                        التمرين</h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="$set('categoryFilter', null)"
                            class="px-4 py-2 rounded-xl text-sm font-bold transition-all border {{ $categoryFilter === null ? 'bg-gymz-accent text-white border-transparent' : 'bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                            الكل
                        </button>
                        @foreach ($categories as $category)
                            <button type="button" wire:click="$set('categoryFilter', {{ $category->id }})"
                                class="px-4 py-2 rounded-xl text-sm font-bold transition-all border {{ $categoryFilter === $category->id ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Targets --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider">العضلة
                        المطلوبة</h4>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="$set('targetFilter', null)"
                            class="px-4 py-2 rounded-xl text-sm font-bold transition-all border {{ $targetFilter === null ? 'bg-gymz-accent text-white border-transparent' : 'bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                            الكل
                        </button>
                        @foreach ($targets as $target)
                            {{-- Hide target if Category is selected and target doesn't belong to it --}}
                            @if (!$categoryFilter || $target->workout_category_id === $categoryFilter)
                                <button type="button" wire:click="$set('targetFilter', {{ $target->id }})"
                                    class="px-4 py-2 rounded-xl text-sm font-bold transition-all border {{ $targetFilter === $target->id ? 'bg-gymz-accent text-white border-transparent shadow-sm' : 'bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-transparent hover:bg-black/10 dark:hover:bg-white/10' }}">
                                    {{ $target->name }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Footer Action --}}
            <div class="p-4 border-t border-black/5 dark:border-white/10 bg-black/5 dark:bg-white/5 mt-auto">
                <button x-data x-on:click.prevent="$dispatch('close')" type="button"
                    class="w-full py-4 rounded-2xl bg-gray-900 dark:bg-white text-white dark:text-black font-bold text-[17px] active:scale-95 transition-all shadow-lg shadow-gray-900/10">
                    عرض النتائج
                </button>
            </div>
        </div>
    </x-modal>
</div>
