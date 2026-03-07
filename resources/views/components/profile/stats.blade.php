@props(['user', 'photo'])

<div
    class="bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-[2rem] p-6 mb-6">
    {{-- Header: Avatar + Info --}}
    <div class="flex items-center gap-4 mb-5">
        {{-- Clickable Avatar for Photo Upload --}}
        <x-photo-upload :user="$user" :photo="$photo" sizeClasses="w-20 h-20" />

        <div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
            @if ($user->phone)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</p>
            @endif
        </div>
    </div>

    {{-- Badges Row --}}
    <div class="flex flex-wrap items-center gap-2 mb-5">
        {{-- Level Badge --}}
        @php
            $levelClasses = match ($user->level) {
                \App\Enums\UserLevel::Beginner => 'bg-blue-500/15 text-blue-600 dark:text-blue-400 border-blue-500/30',
                \App\Enums\UserLevel::Mid => 'bg-gymz-accent/15 text-gymz-accent border-gymz-accent/30',
                \App\Enums\UserLevel::Pro => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                default
                    => 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-white/10 dark:text-white/50 dark:border-white/10',
            };
        @endphp
        <span class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $levelClasses }}">
            {{ $user->level?->getLabel() ?? 'مبتدئ' }}
        </span>

        {{-- Gender Badge --}}
        @if ($user->gender)
            <span
                class="px-3 py-1 rounded-full text-[11px] font-bold border bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/70 border-black/5 dark:border-white/10">
                {{ $user->gender->getLabel() }}
            </span>
        @endif

        {{-- DOB Badge --}}
        @if ($user->dob)
            <span
                class="px-3 py-1 rounded-full text-[11px] font-bold border bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/70 border-black/5 dark:border-white/10">
                {{ \Carbon\Carbon::parse($user->dob)->translatedFormat('j F Y') }}
            </span>
        @endif
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-3">
        {{-- Glutes Balance --}}
        <div class="bg-black/5 dark:bg-white/5 rounded-2xl p-4 text-center">
            <div class="flex items-center justify-center gap-1.5 mb-2">
                <img src="{{ asset('images/peach.svg') }}" class="w-4 h-4 scale-125" alt="glutes">
                <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">الجلوتس</span>
            </div>
            <p class="text-xl font-black text-gray-900 dark:text-white">
                {{ number_format($user->glutes_balance) }}</p>
        </div>

        {{-- Reliability Score --}}
        @php
            $score = $user->reliability_score;
            $scoreColor = $score > 80 ? 'text-green-500' : ($score > 50 ? 'text-amber-500' : 'text-red-500');
        @endphp
        <div class="bg-black/5 dark:bg-white/5 rounded-2xl p-4 text-center">
            <div class="flex items-center justify-center gap-1.5 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-4 h-4 {{ $scoreColor }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">الموثوقية</span>
            </div>
            <p class="text-xl font-black {{ $scoreColor }}">{{ $score }}%</p>
        </div>
    </div>
</div>
