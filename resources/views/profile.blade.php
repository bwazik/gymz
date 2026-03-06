<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    {{-- Fitness CV Card --}}
    <div class="bg-white/80 dark:bg-white/10 backdrop-blur-lg border border-gray-200 dark:border-white/20 rounded-3xl p-6 mb-6 text-gray-900 dark:text-white shadow-lg shadow-gray-200/50 dark:shadow-none">
        {{-- Header: Avatar + Name --}}
        <div class="flex items-center gap-4 mb-5">
            @if (auth()->user()->image_path)
                <img src="{{ Storage::url(auth()->user()->image_path) }}" alt="{{ auth()->user()->name }}"
                    class="w-16 h-16 rounded-full object-cover ring-2 ring-gymz-accent/50">
            @else
                <div
                    class="w-16 h-16 rounded-full bg-gymz-accent/20 flex items-center justify-center ring-2 ring-gymz-accent/50">
                    <span class="text-xl font-bold text-gymz-accent">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
            @endif
            <div>
                <h3 class="text-lg font-bold">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-white/50">{{ auth()->user()->email }}</p>
            </div>
        </div>

        {{-- Badges Row --}}
        <div class="flex items-center gap-2 mb-5">
            {{-- Level Badge --}}
            @php
                $levelClasses = match (auth()->user()->level) {
                    \App\Enums\UserLevel::Beginner => 'bg-blue-500/15 text-blue-600 dark:text-blue-400 border-blue-500/30',
                    \App\Enums\UserLevel::Mid => 'bg-gymz-accent/15 text-gymz-accent border-gymz-accent/30',
                    \App\Enums\UserLevel::Pro => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                    default => 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-white/10 dark:text-white/50 dark:border-white/10',
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $levelClasses }}">
                {{ auth()->user()->level?->getLabel() ?? 'N/A' }}
            </span>

            {{-- Gender Badge --}}
            @if (auth()->user()->gender)
                @php
                    $genderClasses = match (auth()->user()->gender) {
                        \App\Enums\Gender::Male => 'bg-blue-500/15 text-blue-600 dark:text-blue-400 border-blue-500/30',
                        \App\Enums\Gender::Female => 'bg-pink-500/15 text-pink-600 dark:text-pink-400 border-pink-500/30',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $genderClasses }}">
                    {{ auth()->user()->gender->getLabel() }}
                </span>
            @endif
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Glutes Balance --}}
            <div class="bg-gray-50 border border-gray-200 dark:bg-white/5 dark:border-white/10 rounded-2xl p-4 text-center">
                <div class="flex items-center justify-center gap-1.5 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-amber-500 dark:text-amber-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <span class="text-xs text-gray-500 dark:text-white/50 font-medium">Glutes</span>
                </div>
                <p class="text-2xl font-bold text-amber-500 dark:text-amber-400">{{ number_format(auth()->user()->glutes_balance) }}</p>
            </div>

            {{-- Reliability Score --}}
            @php
                $score = auth()->user()->reliability_score;
                $scoreColor = $score > 80 ? 'text-gymz-accent' : ($score > 50 ? 'text-amber-500 dark:text-amber-400' : 'text-red-500 dark:text-red-400');
            @endphp
            <div class="bg-gray-50 border border-gray-200 dark:bg-white/5 dark:border-white/10 rounded-2xl p-4 text-center">
                <div class="flex items-center justify-center gap-1.5 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 {{ $scoreColor }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    <span class="text-xs text-gray-500 dark:text-white/50 font-medium">Reliability</span>
                </div>
                <p class="text-2xl font-bold {{ $scoreColor }}">{{ $score }}%</p>
            </div>
        </div>
    </div>

    {{-- Settings Sections --}}
    <div class="space-y-4">
        {{-- Update Profile Information --}}
        <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-gray-200 dark:border-white/10 rounded-2xl p-5 shadow-sm dark:shadow-none">
            <livewire:profile.update-profile-information-form />
        </div>

        {{-- Update Password --}}
        <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-gray-200 dark:border-white/10 rounded-2xl p-5 shadow-sm dark:shadow-none">
            <livewire:profile.update-password-form />
        </div>

        {{-- Delete Account --}}
        <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-gray-200 dark:border-white/10 rounded-2xl p-5 shadow-sm dark:shadow-none">
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
