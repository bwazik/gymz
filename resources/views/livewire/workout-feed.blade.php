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
                    <img src="{{ $intent->user->image_path ? Storage::url($intent->user->image_path) : asset('images/default.jpg') }}"
                        alt="{{ $intent->user->name }}"
                        class="w-11 h-11 rounded-full object-cover border border-black/5 dark:border-white/10">
                    <div>
                        <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $intent->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $intent->user->level?->getLabel() }}</p>
                    </div>
                @endauth
                @guest
                    <div class="w-11 h-11 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center backdrop-blur-sm border border-black/5">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm text-gray-900 dark:text-white filter blur-[2px] select-none">عضو مخفي</p>
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
                    <a href="{{ route('google.login') }}" class="px-5 py-2 text-[11px] font-bold rounded-full bg-gymz-accent text-white active:scale-95 transition-transform duration-200 shadow-lg shadow-gymz-accent/20">
                        سجل وشاركهم التمرينة 🚀
                    </a>
                @endguest
            </div>
        </x-glass-card>
    @empty
        {{-- Empty State --}}
        <x-glass-card class="p-8 text-center">
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-white/30">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                </svg>
            </div>
            <h3 class="text-gray-700 dark:text-white/70 font-medium mb-1">مفيش حد لسه نزل تمرين</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">نزل تمرين و خلي الناس تيجي تتمرن معاك</p>
        </x-glass-card>
    @endforelse
</div>
