<nav
    class="fixed bottom-6 w-[88%] max-w-[380px] left-1/2 bg-white/40 dark:bg-white/5 backdrop-blur-3xl saturate-[1.5] border border-white/50 dark:border-white/10 rounded-full flex justify-around items-center py-2 px-2 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3),inset_0_1px_1px_rgba(255,255,255,0.9)] dark:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.8),inset_0_1px_1px_rgba(255,255,255,0.15)] z-50 transition-transform duration-[50ms] ease-linear"
    style="transform: translateX(-50%);"
    :style="`transform: translateX(-50%) perspective(1000px) translateZ(10px) rotateX(${rotateX}deg) rotateY(${rotateY}deg);`">

    <!-- Feed (Home) -->
    <a href="{{ route('dashboard') }}"
        class="relative flex flex-col items-center justify-center px-4 py-2 rounded-full transition-all duration-300 active:scale-95 group 
        {{ request()->routeIs('dashboard') ? 'bg-white/60 dark:bg-white/10 shadow-[inset_0_1px_2px_rgba(255,255,255,0.8)] dark:shadow-[inset_0_1px_2px_rgba(255,255,255,0.15)]' : 'hover:bg-white/30 dark:hover:bg-white/5' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('dashboard') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>
        <span
            class="{{ request()->routeIs('dashboard') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }} text-[10px] mt-1 font-bold transition-colors duration-300">الفيد</span>
    </a>

    <!-- Requests -->
    <a href="{{ route('requests') }}"
        class="relative flex flex-col items-center justify-center px-4 py-2 rounded-full transition-all duration-300 active:scale-95 group 
        {{ request()->routeIs('requests') ? 'bg-white/60 dark:bg-white/10 shadow-[inset_0_1px_2px_rgba(255,255,255,0.8)] dark:shadow-[inset_0_1px_2px_rgba(255,255,255,0.15)]' : 'hover:bg-white/30 dark:hover:bg-white/5' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('requests') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
        </svg>
        <span
            class="{{ request()->routeIs('requests') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }} text-[10px] mt-1 font-bold transition-colors duration-300">الطلبات</span>
    </a>

    <!-- Session (QR) -->
    <a href="{{ route('sessions') }}"
        class="relative flex flex-col items-center justify-center px-4 py-2 rounded-full transition-all duration-300 active:scale-95 group 
        {{ request()->routeIs('sessions') ? 'bg-white/60 dark:bg-white/10 shadow-[inset_0_1px_2px_rgba(255,255,255,0.8)] dark:shadow-[inset_0_1px_2px_rgba(255,255,255,0.15)]' : 'hover:bg-white/30 dark:hover:bg-white/5' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('sessions') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
        </svg>
        <span
            class="{{ request()->routeIs('sessions') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }} text-[10px] mt-1 font-bold transition-colors duration-300">الجلسة</span>
    </a>

    <!-- Profile -->
    <a href="{{ route('profile') }}"
        class="relative flex flex-col items-center justify-center px-4 py-2 rounded-full transition-all duration-300 active:scale-95 group 
        {{ request()->routeIs('profile') ? 'bg-white/60 dark:bg-white/10 shadow-[inset_0_1px_2px_rgba(255,255,255,0.8)] dark:shadow-[inset_0_1px_2px_rgba(255,255,255,0.15)]' : 'hover:bg-white/30 dark:hover:bg-white/5' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('profile') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }}">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span
            class="{{ request()->routeIs('profile') ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }} text-[10px] mt-1 font-bold transition-colors duration-300">الملف</span>
    </a>

</nav>
