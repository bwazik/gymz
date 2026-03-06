<div>
    {{-- Floating Action Button --}}
    <button wire:click="$set('showModal', true)"
        class="fixed bottom-24 right-6 z-40 w-14 h-14 rounded-full bg-gradient-to-br from-gymz-accent to-emerald-600 text-white flex items-center justify-center shadow-lg shadow-gymz-accent/30 hover:scale-110 active:scale-95 transition-all duration-200 border border-white/20 backdrop-blur-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="w-7 h-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    {{-- Modal Overlay --}}
    @if ($showModal)
        <div x-data="{ open: true }" x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-gray-900/60 dark:bg-black/60 backdrop-blur-sm flex items-end justify-center"
            wire:click.self="$set('showModal', false)">
            {{-- Bottom Sheet Modal --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="bg-white/90 dark:bg-white/10 border border-gray-200 dark:border-white/20 backdrop-blur-xl p-6 rounded-t-3xl w-full max-w-md shadow-[0_-4px_30px_rgba(0,0,0,0.1)] dark:shadow-[0_-4px_30px_rgba(0,0,0,0.3)]"
                @click.stop>
                {{-- Drag Handle --}}
                <div class="flex justify-center mb-4">
                    <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">🏋️ Post Workout Intent</h3>

                {{-- Flash Message --}}
                @if (session()->has('message'))
                    <div
                        class="mb-4 px-4 py-2 rounded-xl bg-gymz-accent/15 text-gymz-accent text-sm font-medium border border-gymz-accent/30">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit="save" class="space-y-4">
                    {{-- Gym Select --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">Gym</label>
                        <select wire:model="gym_id"
                            class="w-full rounded-xl bg-white/50 dark:bg-white/5 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:border-gymz-accent/50 focus:ring-1 focus:ring-gymz-accent/30 transition-colors placeholder-gray-400 dark:placeholder-white/30">
                            <option value="">Select a gym...</option>
                            @foreach ($gyms as $gym)
                                <option value="{{ $gym->id }}">{{ $gym->name }}</option>
                            @endforeach
                        </select>
                        @error('gym_id')
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Category Select --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">Workout Category</label>
                        <select wire:model.live="workout_category_id"
                            class="w-full rounded-xl bg-white/50 dark:bg-white/5 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:border-gymz-accent/50 focus:ring-1 focus:ring-gymz-accent/30 transition-colors placeholder-gray-400 dark:placeholder-white/30">
                            <option value="">Select a category...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('workout_category_id')
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Target Select (dynamic) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">Workout Target</label>
                        <select wire:model="workout_target_id"
                            class="w-full rounded-xl bg-white/50 dark:bg-white/5 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:border-gymz-accent/50 focus:ring-1 focus:ring-gymz-accent/30 transition-colors disabled:opacity-40 disabled:cursor-not-allowed placeholder-gray-400 dark:placeholder-white/30"
                            @if ($targets->isEmpty()) disabled @endif>
                            <option value="">
                                {{ $targets->isEmpty() ? 'Pick a category first...' : 'Select a target...' }}</option>
                            @foreach ($targets as $target)
                                <option value="{{ $target->id }}">{{ $target->name }}</option>
                            @endforeach
                        </select>
                        @error('workout_target_id')
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Start Time --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">Start Time</label>
                        <input type="datetime-local" wire:model="start_time"
                            class="w-full rounded-xl bg-white/50 dark:bg-white/5 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:border-gymz-accent/50 focus:ring-1 focus:ring-gymz-accent/30 transition-colors dark:[color-scheme:dark]">
                        @error('start_time')
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Guest Pass Toggle --}}
                    <div class="flex items-center justify-between py-1">
                        <div>
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Guest Pass Available</label>
                            <p class="text-xs text-gray-500 dark:text-white/40">Invite someone who doesn't have a membership</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="has_invitation" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-300 dark:bg-white/10 rounded-full peer peer-checked:bg-gymz-accent/60 peer-focus:ring-2 peer-focus:ring-gymz-accent/30 transition-colors after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full border border-gray-300 dark:border-white/10">
                            </div>
                        </label>
                    </div>

                    {{-- Note --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-white/60 mb-1.5">Note <span
                                class="text-gray-400 dark:text-white/30">(optional)</span></label>
                        <textarea wire:model="note" rows="2" placeholder="Looking for a spotter, want to hit PRs today..."
                            class="w-full rounded-xl bg-white/50 dark:bg-white/5 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm px-4 py-2.5 focus:border-gymz-accent/50 focus:ring-1 focus:ring-gymz-accent/30 transition-colors placeholder-gray-400 dark:placeholder-white/20 resize-none"></textarea>
                        @error('note')
                            <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-gymz-accent to-emerald-600 text-white font-semibold text-sm hover:opacity-90 transition-opacity shadow-lg shadow-gymz-accent/25">
                            <span wire:loading.remove wire:target="save">Post Intent 🔥</span>
                            <span wire:loading wire:target="save">Posting...</span>
                        </button>
                        <button type="button" wire:click="$set('showModal', false)"
                            class="px-5 py-2.5 rounded-xl bg-gray-100 border border-gray-300 text-gray-700 hover:bg-gray-200 dark:bg-white/5 dark:border-white/10 dark:text-white/60 font-medium text-sm dark:hover:bg-white/10 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
