{{-- Intent Card — Shared Partial --}}
{{-- @param \App\Models\WorkoutIntent $intent --}}
{{-- @param array $sentRequestIntentIds --}}
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
            <button type="button" wire:click="$dispatch('openTrustProfile', { userId: {{ $intent->user_id }} })"
                class="relative group focus:outline-none active:scale-95 transition-transform duration-200">
                <img src="{{ $intent->user->image_path ? (Str::startsWith($intent->user->image_path, 'http') ? $intent->user->image_path : Storage::url($intent->user->image_path)) : asset('images/default.jpg') }}"
                    referrerpolicy="no-referrer" alt="{{ $intent->user->name }}"
                    class="w-11 h-11 rounded-full object-cover border border-black/5 dark:border-white/10 ring-2 ring-gymz-accent/60 group-hover:ring-gymz-accent group-active:ring-gymz-accent ring-offset-2 ring-offset-[#f2f2f7] dark:ring-offset-[#000000] shadow-sm transition-all">

                {{-- Indicator Badge --}}
                <div
                    class="absolute -bottom-0.5 -right-0.5 bg-white dark:bg-[#1c1c1e] text-gymz-accent rounded-full p-0.5 shadow-sm border border-black/5 dark:border-white/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        class="w-3.5 h-3.5">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
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
            @if ($intent->gym->logo_path)
                <img src="{{ Str::startsWith($intent->gym->logo_path, 'http') ? $intent->gym->logo_path : Storage::url($intent->gym->logo_path) }}"
                    alt="{{ $intent->gym->name }}"
                    class="w-4 h-4 rounded-full object-contain mix-blend-multiply dark:mix-blend-normal opacity-90 shrink-0">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 text-gymz-accent shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            @endif
            <span>{{ $intent->gym->name }}</span>
        </div>

        {{-- Workout Target --}}
        <div
            class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-gymz-accent/10 text-gymz-accent dark:bg-gymz-accent/15">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-3.5 h-3.5 shrink-0 opacity-70">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" />
            </svg>
            <span class="truncate max-w-[120px]">{{ $intent->workoutTarget->workoutCategory->name }} -
                {{ $intent->workoutTarget->name }}</span>
        </div>
    </div>

    {{-- Footer: Time + Action --}}
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
