@props(['balance'])

<div
    class="relative w-full rounded-[2rem] p-6 text-white overflow-hidden shadow-2xl bg-gradient-to-br from-[#1a1a1c] to-[#2d2d30] border border-white/10">
    {{-- Ambient Glass Effects --}}
    <div class="absolute top-0 right-0 w-32 h-32 bg-gymz-accent/30 rounded-full blur-[40px] -mr-10 -mt-10"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500/20 rounded-full blur-[50px] -ml-10 -mb-10"></div>

    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-white/60 text-sm font-medium mb-1">رصيد الجلوتس الحالي</p>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-4xl font-black tracking-tight">{{ number_format($balance) }}</h2>
                    <img src="{{ asset('images/peach.svg') }}" class="w-6 h-6 animate-pulse" alt="glutes">
                </div>
            </div>

            <div
                class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5 text-white/80">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                </svg>
            </div>
        </div>

        <div class="flex justify-between items-end">
            <div>
                <p class="text-white/50 text-xs font-mono tracking-wider">GYMZ • REWARDS MEMBER</p>
                <p class="text-white/90 font-bold mt-1 tracking-wide">{{ Auth::user()->name }}</p>
            </div>

            <div class="flex gap-1.5 opacity-80">
                <div class="w-2.5 h-2.5 rounded-full bg-gymz-accent"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-white/40"></div>
            </div>
        </div>
    </div>
</div>
