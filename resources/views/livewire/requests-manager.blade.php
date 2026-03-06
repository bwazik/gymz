<div>
    {{-- Section Title --}}
    <div class="mb-4 mt-4">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">الطلبات</h2>
    </div>

    {{-- iOS Segmented Control --}}
    <div class="flex bg-black/5 dark:bg-white/10 rounded-[1.25rem] p-1 mb-6 border border-transparent">
        <button wire:click="$set('activeTab', 'incoming')"
            class="flex-1 py-2.5 text-sm font-bold rounded-[1rem] transition-all duration-300 active:scale-95
                {{ $activeTab === 'incoming'
                    ? 'bg-white dark:bg-[#2c2c2e] text-gray-900 dark:text-white shadow-sm'
                    : 'text-gray-500 dark:text-white/50' }}">
            طلبات جيالي
        </button>
        <button wire:click="$set('activeTab', 'outgoing')"
            class="flex-1 py-2.5 text-sm font-bold rounded-[1rem] transition-all duration-300 active:scale-95
                {{ $activeTab === 'outgoing'
                    ? 'bg-white dark:bg-[#2c2c2e] text-gray-900 dark:text-white shadow-sm'
                    : 'text-gray-500 dark:text-white/50' }}">
            طلبات بعتها
        </button>
    </div>

    {{-- Incoming Tab --}}
    @if ($activeTab === 'incoming')
        @forelse ($this->incomingRequests as $request)
            <x-glass-card class="mb-4">
                {{-- Sender Info --}}
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $request->sender->image_path ? (Str::startsWith($request->sender->image_path, 'http') ? $request->sender->image_path : Storage::url($request->sender->image_path)) : asset('images/default.jpg') }}"
                        alt="{{ $request->sender->name }}"
                        class="w-11 h-11 rounded-full object-cover border border-black/5 dark:border-white/10">
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $request->sender->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->sender->level?->getLabel() }}
                        </p>
                    </div>
                    <span
                        class="text-xs text-gray-400 dark:text-gray-500">{{ $request->created_at->diffForHumans() }}</span>
                </div>

                {{-- Intent Details --}}
                <div class="flex flex-wrap items-center gap-2 mb-4 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 text-gymz-accent shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>{{ $request->workoutIntent->gym->name }}</span>
                    </div>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span
                        class="px-3 py-1 rounded-full text-[11px] font-bold bg-gymz-accent/10 text-gymz-accent dark:bg-gymz-accent/15">
                        {{ $request->workoutIntent->workoutTarget->name }}
                    </span>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span>{{ $request->workoutIntent->start_time->format('g:i A') }}</span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button wire:click="acceptRequest({{ $request->id }})" wire:loading.attr="disabled"
                        class="flex-1 py-2.5 rounded-xl bg-gymz-accent text-white font-bold text-sm active:scale-95 transition-all disabled:opacity-50">
                        <span wire:loading.remove wire:target="acceptRequest({{ $request->id }})">قبول</span>
                        <span wire:loading wire:target="acceptRequest({{ $request->id }})">جاري...</span>
                    </button>
                    <button
                        @click="$dispatch('open-ios-alert', { title: 'رفض الطلب', message: 'متأكد إنك عايز ترفض الطلب ده؟', action: 'rejectRequest', params: {{ $request->id }}, componentId: $wire.$id })"
                        class="flex-1 py-2.5 rounded-xl bg-gray-200/50 dark:bg-white/10 text-gray-700 dark:text-white/70 font-bold text-sm active:scale-95 transition-all disabled:opacity-50">
                        رفض
                    </button>
                </div>
            </x-glass-card>
        @empty
            <x-glass-card class="p-8 text-center">
                <div
                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-black/5 dark:bg-white/5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-700 dark:text-white/70 mb-1">مفيش طلبات لسه</h3>
                <p class="text-sm text-gray-500 dark:text-gray-500">لما حد يطلب يتمرن معاك هيظهر هنا</p>
            </x-glass-card>
        @endforelse
    @endif

    {{-- Outgoing Tab --}}
    @if ($activeTab === 'outgoing')
        @forelse ($this->outgoingRequests as $request)
            <x-glass-card class="mb-4">
                {{-- Intent Owner Info --}}
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $request->workoutIntent->user->image_path ? (Str::startsWith($request->workoutIntent->user->image_path, 'http') ? $request->workoutIntent->user->image_path : Storage::url($request->workoutIntent->user->image_path)) : asset('images/default.jpg') }}"
                        alt="{{ $request->workoutIntent->user->name }}"
                        class="w-11 h-11 rounded-full object-cover border border-black/5 dark:border-white/10">
                    <div class="flex-1">
                        <p class="font-semibold text-sm text-gray-900 dark:text-white">
                            {{ $request->workoutIntent->user->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $request->workoutIntent->user->level?->getLabel() }}</p>
                    </div>

                    {{-- Status Badge --}}
                    @php
                        [$badgeClasses, $badgeLabel] = match ($request->status) {
                            \App\Enums\RequestStatus::Pending => [
                                'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                'في الانتظار',
                            ],
                            \App\Enums\RequestStatus::Accepted => [
                                'bg-gymz-accent/10 text-gymz-accent dark:bg-gymz-accent/15',
                                'مقبول',
                            ],
                            \App\Enums\RequestStatus::Rejected => [
                                'bg-red-500/10 text-red-500 dark:text-red-400',
                                'مرفوض',
                            ],
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold {{ $badgeClasses }}">
                        {{ $badgeLabel }}
                    </span>
                </div>

                {{-- Intent Details --}}
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 text-gymz-accent shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>{{ $request->workoutIntent->gym->name }}</span>
                    </div>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span
                        class="px-3 py-1 rounded-full text-[11px] font-bold bg-gymz-accent/10 text-gymz-accent dark:bg-gymz-accent/15">
                        {{ $request->workoutIntent->workoutTarget->name }}
                    </span>
                    <span class="text-gray-300 dark:text-white/20">·</span>
                    <span>{{ $request->workoutIntent->start_time->format('g:i A') }}</span>
                </div>
            </x-glass-card>
        @empty
            <x-glass-card class="p-8 text-center">
                <div
                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-black/5 dark:bg-white/5 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-700 dark:text-white/70 mb-1">مبعتش طلبات لسه</h3>
                <p class="text-sm text-gray-500 dark:text-gray-500">الطلبات اللي هتبعتها من الفيد هتظهر هنا</p>
            </x-glass-card>
        @endforelse
    @endif
</div>
