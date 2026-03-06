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
@endphp

<nav
    class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-[380px] bg-white/60 dark:bg-[#1c1c1e]/70 backdrop-blur-2xl border border-black/5 dark:border-white/10 rounded-full flex justify-around items-center p-2 shadow-lg z-50 transition-all duration-300">

    @foreach ($navItems as $item)
        @php $isActive = request()->routeIs($item['route']); @endphp

        <a href="{{ route($item['route']) }}"
            class="flex flex-col items-center justify-center gap-0.5 px-3 py-1.5 rounded-full transition-all duration-200 active:scale-95
            {{ $isActive
                ? 'bg-black/10 dark:bg-white/15 text-gray-900 dark:text-white'
                : 'text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="{{ $isActive ? '2' : '1.5' }}" stroke="currentColor"
                class="w-5 h-5 transition-all duration-200">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
            </svg>

            <span class="text-[10px] font-medium leading-tight">{{ $item['label'] }}</span>
        </a>
    @endforeach

</nav>
