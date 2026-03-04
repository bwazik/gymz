<div>
    {{-- Section Title --}}
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-white tracking-tight">🔥 Upcoming Workouts</h2>
        <span class="text-xs text-white/50">Next 24h</span>
    </div>

    @forelse ($intents as $intent)
        {{-- Liquid Glass Card --}}
        <div
            class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-5 mb-4 shadow-[0_4px_30px_rgba(0,0,0,0.1)] text-white transition-all duration-300 hover:bg-white/15 hover:border-white/30">

            {{-- Header: Avatar + Name --}}
            <div class="flex items-center gap-3 mb-4">
                @if ($intent->user->image_path)
                    <img src="{{ Storage::url($intent->user->image_path) }}" alt="{{ $intent->user->name }}"
                        class="w-10 h-10 rounded-full object-cover ring-2 ring-gymz-accent/50">
                @else
                    <div
                        class="w-10 h-10 rounded-full bg-gymz-accent/20 flex items-center justify-center ring-2 ring-gymz-accent/50">
                        <span class="text-sm font-bold text-gymz-accent">
                            {{ strtoupper(substr($intent->user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-sm">{{ $intent->user->name }}</p>
                    <p class="text-xs text-white/50">{{ $intent->user->level?->getLabel() }}</p>
                </div>
            </div>

            {{-- Body: Gym + Target --}}
            <div class="flex items-center gap-4 mb-4">
                {{-- Gym --}}
                <div class="flex items-center gap-1.5 text-sm text-white/70">
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
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gymz-accent/15 text-gymz-accent border border-gymz-accent/30">
                    {{ $intent->workoutTarget->name }}
                </span>
            </div>

            {{-- Footer: Time + Guest Badge + Action --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Time --}}
                    <div class="flex items-center gap-1 text-xs text-white/60">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $intent->start_time->format('g:i A') }}</span>
                        <span class="text-white/30">·</span>
                        <span>{{ $intent->start_time->diffForHumans() }}</span>
                    </div>

                    {{-- Guest Pass Badge --}}
                    @if ($intent->has_invitation)
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                            </svg>
                            Guest Pass
                        </span>
                    @endif
                </div>

                {{-- Action Button --}}
                <button
                    class="px-4 py-1.5 text-xs font-semibold rounded-full bg-gymz-accent text-gymz-dark hover:bg-gymz-accent/90 transition-all duration-200 shadow-lg shadow-gymz-accent/25">
                    Send Request
                </button>
            </div>
        </div>
    @empty
        {{-- Empty State --}}
        <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-3xl p-8 text-center shadow-glass">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-white/5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-white/30">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                </svg>
            </div>
            <h3 class="text-white/70 font-medium mb-1">No workouts nearby</h3>
            <p class="text-sm text-white/40">No one has posted a workout intent in the next 24 hours yet. Be the first!
            </p>
        </div>
    @endforelse
</div>
