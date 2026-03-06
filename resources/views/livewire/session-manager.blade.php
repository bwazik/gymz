<div>
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">⚡ Active Sessions</h2>
    </div>

    @forelse ($this->activeSessions as $session)
        @php
            $isRoleA = auth()->id() === $session->user_a_id;
            $partner = $isRoleA ? $session->userB : $session->userA;
        @endphp

        <div
            class="bg-white/80 dark:bg-white/10 backdrop-blur-lg border border-gray-200 dark:border-white/20 rounded-3xl p-5 mb-4 shadow-[0_4px_30px_rgba(0,0,0,0.05)] dark:shadow-[0_4px_30px_rgba(0,0,0,0.1)] text-gray-900 dark:text-white">

            {{-- Session Header: Partner + Gym --}}
            <div class="flex items-center gap-3 mb-4">
                @if ($partner->image_path)
                    <img src="{{ Storage::url($partner->image_path) }}" alt="{{ $partner->name }}"
                        class="w-10 h-10 rounded-full object-cover ring-2 ring-gymz-accent/50">
                @else
                    <div
                        class="w-10 h-10 rounded-full bg-gymz-accent/20 flex items-center justify-center ring-2 ring-gymz-accent/50">
                        <span class="text-sm font-bold text-gymz-accent">
                            {{ strtoupper(substr($partner->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="font-semibold text-sm">{{ $partner->name }}</p>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-white/50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-3.5 h-3.5 text-gymz-accent">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>{{ $session->workoutIntent->gym->name }}</span>
                    </div>
                </div>

                {{-- Status Badge --}}
                @if ($session->status === \App\Enums\SessionStatus::Scheduled)
                    <span
                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-500/15 text-blue-600 dark:text-blue-400 border border-blue-500/30">
                        Scheduled
                    </span>
                @elseif ($session->status === \App\Enums\SessionStatus::InProgress)
                    <span
                        class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gymz-accent/15 text-gymz-accent border border-gymz-accent/30 animate-pulse">
                        🔥 In Progress
                    </span>
                @endif
            </div>

            {{-- Scheduled: QR / Verification --}}
            @if ($session->status === \App\Enums\SessionStatus::Scheduled)
                @if ($isRoleA)
                    {{-- Role A: Show QR Code --}}
                    <div class="bg-gray-50 border border-gray-200 dark:bg-white/5 dark:border-white/10 rounded-2xl p-6 text-center">
                        <p class="text-xs text-gray-500 dark:text-white/50 mb-4 font-medium">Show this QR to your workout partner</p>
                        <div class="inline-block bg-white rounded-2xl p-4 mb-4 border border-gray-200 dark:border-none shadow-sm">
                            {!! QrCode::size(150)->style('round')->color(15, 23, 42)->generate($session->qr_token) !!}
                        </div>
                        <div class="mt-3">
                            <p class="text-[10px] text-gray-400 dark:text-white/30 mb-1">Or share this token manually:</p>
                            <code
                                class="text-xs bg-white border-gray-200 dark:bg-white/10 px-3 py-1.5 rounded-lg text-gymz-accent font-mono select-all dark:border-white/10 border shadow-sm">
                                {{ $session->qr_token }}
                            </code>
                        </div>
                    </div>
                @else
                    {{-- Role B: Scan / Enter Token --}}
                    <div class="bg-gray-50 border border-gray-200 dark:bg-white/5 dark:border-white/10 rounded-2xl p-5">
                        <p class="text-xs text-gray-500 dark:text-white/50 mb-3 font-medium">Enter the token from your partner's QR code
                        </p>
                        <div class="flex gap-2">
                            <input type="text" wire:model="scannedToken" placeholder="Paste token here..."
                                class="flex-1 rounded-xl bg-white border border-gray-300 text-gray-900 dark:bg-white/5 dark:border-white/10 dark:text-white text-sm px-4 py-2.5 focus:border-gymz-accent/50 focus:ring-1 focus:ring-gymz-accent/30 transition-colors placeholder-gray-400 dark:placeholder-white/20 font-mono">
                            <button wire:click="verifyToken({{ $session->id }})" wire:loading.attr="disabled"
                                class="px-4 py-2.5 rounded-xl bg-gymz-accent text-white font-semibold text-sm hover:bg-gymz-accent/90 transition-all disabled:opacity-50 shadow-lg shadow-gymz-accent/25">
                                <span wire:loading.remove wire:target="verifyToken({{ $session->id }})">Verify</span>
                                <span wire:loading wire:target="verifyToken({{ $session->id }})">...</span>
                            </button>
                        </div>
                        @error('scannedToken')
                            <p class="text-red-500 dark:text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            @elseif ($session->status === \App\Enums\SessionStatus::InProgress)
                {{-- In Progress: End Workout --}}
                <div class="bg-gymz-accent/5 border border-gymz-accent/20 rounded-2xl p-5 text-center">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gymz-accent/15 text-gymz-accent text-sm font-semibold mb-4 animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-gymz-accent"></span>
                        Workout In Progress!
                    </div>
                    <p class="text-xs text-gray-500 dark:text-white/50 mb-4">When you're done, end the session to claim your Glutes reward.
                    </p>
                    <button wire:click="endSession({{ $session->id }})" wire:loading.attr="disabled"
                        wire:confirm="End this workout and claim 10 Glutes for both partners?"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-gymz-accent to-emerald-600 text-white font-bold text-sm hover:opacity-90 transition-opacity shadow-lg shadow-gymz-accent/25 disabled:opacity-50">
                        <span wire:loading.remove wire:target="endSession({{ $session->id }})">🏆 End Workout & Claim
                            10 Glutes</span>
                        <span wire:loading wire:target="endSession({{ $session->id }})">Processing...</span>
                    </button>
                </div>
            @endif
        </div>
    @empty
        {{-- Empty State --}}
        <div class="bg-white/50 dark:bg-white/5 backdrop-blur-lg border border-gray-200 dark:border-white/10 rounded-3xl p-8 text-center shadow-glass">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-white/30">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                </svg>
            </div>
            <h3 class="text-gray-700 dark:text-white/70 font-medium mb-1">No active sessions</h3>
            <p class="text-sm text-gray-500 dark:text-white/40">Once a workout request is accepted, your session will appear here with a QR
                code for verification.</p>
        </div>
    @endforelse
</div>
