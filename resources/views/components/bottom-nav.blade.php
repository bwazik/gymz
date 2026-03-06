@php
    $navItems = [
        [
            'route' => 'dashboard',
            'label' => 'الفيد',
            'icon' =>
                'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
        ],
        [
            'route' => 'requests',
            'label' => 'الطلبات',
            'icon' =>
                'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
        ],
        [
            'route' => 'sessions',
            'label' => 'الجلسة',
            'icon' =>
                'M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z',
        ],
        [
            'route' => 'profile',
            'label' => 'الملف',
            'icon' =>
                'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z',
        ],
    ];

    $currentRoute = request()->route()->getName();
    $activeIndex = collect($navItems)->search(fn($item) => request()->routeIs($item['route']));
    $activeIndex = $activeIndex !== false ? $activeIndex : 0;
@endphp

<nav
    class="fixed bottom-8 left-1/2 -translate-x-1/2 flex items-center p-1.5 rounded-[2rem] bg-white/60 dark:bg-[#1c1c1e]/60 backdrop-blur-3xl border border-white/40 dark:border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.12),inset_0_1px_1px_rgba(255,255,255,0.8)] dark:shadow-[0_8px_32px_rgba(0,0,0,0.4),inset_0_1px_1px_rgba(255,255,255,0.1)] z-50 w-[90%] max-w-[360px]">

    {{-- Active Pill Background --}}
    <div id="active-pill"
        class="absolute h-[calc(100%-12px)] w-[calc(25%-6px)] bg-gray-400/20 dark:bg-white/15 rounded-full transition-transform duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] pointer-events-none"
        style="right: 6px; transform: translateX({{ $activeIndex * -100 }}%);">
    </div>

    @foreach ($navItems as $item)
        @php $isActive = request()->routeIs($item['route']); @endphp

        <a href="{{ route($item['route']) }}" wire:navigate data-route="{{ $item['route'] }}"
            data-active="{{ $isActive ? 'true' : 'false' }}"
            class="flex-1 flex flex-col items-center justify-center py-2 relative z-10 transition-colors duration-200
            {{ $isActive ? 'text-[#ff2d55]' : 'text-gray-500 dark:text-gray-400' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $isActive ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
            </svg>

            <span class="text-[10px] font-semibold mt-0.5">
                {{ $item['label'] }}
            </span>
        </a>
    @endforeach
</nav>
