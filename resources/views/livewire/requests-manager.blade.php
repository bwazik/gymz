<div>
    {{-- Tab Toggle --}}
    <div class="flex bg-gray-100 dark:bg-white/5 rounded-2xl p-1 mb-6 border border-gray-200 dark:border-white/10">
        <button wire:click="$set('activeTab', 'incoming')"
            class="flex-1 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200
                {{ $activeTab === 'incoming' ? 'bg-gymz-accent text-white shadow-lg shadow-gymz-accent/25' : 'text-gray-500 hover:text-gray-900 dark:text-white/50 dark:hover:text-white/80' }}">
            Incoming
        </button>
        <button wire:click="$set('activeTab', 'outgoing')"
            class="flex-1 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200
                {{ $activeTab === 'outgoing' ? 'bg-gymz-accent text-white shadow-lg shadow-gymz-accent/25' : 'text-gray-500 hover:text-gray-900 dark:text-white/50 dark:hover:text-white/80' }}">
            Outgoing
        </button>
    </div>

    {{-- Incoming Tab --}}
    @if ($activeTab === 'incoming')
        @forelse ($this->incomingRequests as $request)
            <div
                class="bg-white/80 dark:bg-white/10 backdrop-blur-lg border border-gray-200 dark:border-white/20 rounded-3xl p-5 mb-4 shadow-[0_4px_30px_rgba(0,0,0,0.05)] dark:shadow-[0_4px_30px_rgba(0,0,0,0.1)] text-gray-900 dark:text-white">
                {{-- Sender Info --}}
                <div class="flex items-center gap-3 mb-3">
                    @if ($request->sender->image_path)
                        <img src="{{ Storage::url($request->sender->image_path) }}" alt="{{ $request->sender->name }}"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-gymz-accent/50">
                    @else
                        <div
                            class="w-10 h-10 rounded-full bg-gymz-accent/20 flex items-center justify-center ring-2 ring-gymz-accent/50">
                            <span class="text-sm font-bold text-gymz-accent">
                                {{ strtoupper(substr($request->sender->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ $request->sender->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-white/50">{{ $request->sender->level?->getLabel() }}</p>
                    </div>
                    <span class="text-xs text-gray-400 dark:text-white/40">{{ $request->created_at->diffForHumans() }}</span>
                </div>

                {{-- Intent Details --}}
                <div class="flex items-center gap-3 mb-4 text-sm text-gray-600 dark:text-white/60">
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 text-gymz-accent">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>{{ $request->workoutIntent->gym->name }}</span>
                    </div>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span
                        class="px-2 py-0.5 rounded-full text-xs bg-gymz-accent/15 text-gymz-accent border border-gymz-accent/30">
                        {{ $request->workoutIntent->workoutTarget->name }}
                    </span>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span>{{ $request->workoutIntent->start_time->format('g:i A') }}</span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button wire:click="acceptRequest({{ $request->id }})" wire:loading.attr="disabled"
                        class="flex-1 py-2 rounded-xl bg-gymz-accent/15 text-gymz-accent font-semibold text-sm border border-gymz-accent/30 hover:bg-gymz-accent/25 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="acceptRequest({{ $request->id }})">✓ Accept</span>
                        <span wire:loading wire:target="acceptRequest({{ $request->id }})">Accepting...</span>
                    </button>
                    <button wire:click="rejectRequest({{ $request->id }})" wire:loading.attr="disabled"
                        class="flex-1 py-2 rounded-xl bg-red-500/10 text-red-500 dark:text-red-400 font-semibold text-sm border border-red-500/20 hover:bg-red-500/20 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="rejectRequest({{ $request->id }})">✕ Reject</span>
                        <span wire:loading wire:target="rejectRequest({{ $request->id }})">Rejecting...</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white/50 dark:bg-white/5 backdrop-blur-lg border border-gray-200 dark:border-white/10 rounded-3xl p-8 text-center shadow-glass">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-white/30">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
                    </svg>
                </div>
                <h3 class="text-gray-700 dark:text-white/70 font-medium mb-1">No incoming requests</h3>
                <p class="text-sm text-gray-500 dark:text-white/40">When someone wants to join your workout, you'll see it here.</p>
            </div>
        @endforelse
    @endif

    {{-- Outgoing Tab --}}
    @if ($activeTab === 'outgoing')
        @forelse ($this->outgoingRequests as $request)
            <div
                class="bg-white/80 dark:bg-white/10 backdrop-blur-lg border border-gray-200 dark:border-white/20 rounded-3xl p-5 mb-4 shadow-[0_4px_30px_rgba(0,0,0,0.05)] dark:shadow-[0_4px_30px_rgba(0,0,0,0.1)] text-gray-900 dark:text-white">
                {{-- Intent Owner Info --}}
                <div class="flex items-center gap-3 mb-3">
                    @if ($request->workoutIntent->user->image_path)
                        <img src="{{ Storage::url($request->workoutIntent->user->image_path) }}"
                            alt="{{ $request->workoutIntent->user->name }}"
                            class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-200 dark:ring-white/20">
                    @else
                        <div
                            class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center ring-2 ring-gray-200 dark:ring-white/20">
                            <span class="text-sm font-bold text-gray-500 dark:text-white/60">
                                {{ strtoupper(substr($request->workoutIntent->user->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-semibold text-sm">{{ $request->workoutIntent->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-white/50">{{ $request->workoutIntent->user->level?->getLabel() }}</p>
                    </div>

                    {{-- Status Badge --}}
                    @php
                        $statusClasses = match ($request->status) {
                            \App\Enums\RequestStatus::Pending => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                            \App\Enums\RequestStatus::Accepted => 'bg-gymz-accent/15 text-gymz-accent border-gymz-accent/30',
                            \App\Enums\RequestStatus::Rejected => 'bg-red-500/10 text-red-500 dark:text-red-400 border-red-500/20',
                        };
                    @endphp
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClasses }}">
                        {{ $request->status->name }}
                    </span>
                </div>

                {{-- Intent Details --}}
                <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-white/60">
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 text-gymz-accent">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>{{ $request->workoutIntent->gym->name }}</span>
                    </div>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span
                        class="px-2 py-0.5 rounded-full text-xs bg-gymz-accent/15 text-gymz-accent border border-gymz-accent/30">
                        {{ $request->workoutIntent->workoutTarget->name }}
                    </span>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span>{{ $request->workoutIntent->start_time->format('g:i A') }}</span>
                </div>
            </div>
        @empty
            <div class="bg-white/50 dark:bg-white/5 backdrop-blur-lg border border-gray-200 dark:border-white/10 rounded-3xl p-8 text-center shadow-glass">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-white/30">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </div>
                <h3 class="text-gray-700 dark:text-white/70 font-medium mb-1">No outgoing requests</h3>
                <p class="text-sm text-gray-500 dark:text-white/40">Requests you send from the feed will appear here.</p>
            </div>
        @endforelse
    @endif
</div>
