<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f2f2f7" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="GymZ">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <title>{{ config('app.name', 'GymZ') }}</title>

    <!-- Prevent FOUC for Dark Mode -->
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-tajawal antialiased bg-[#f2f2f7] dark:bg-black text-gray-900 dark:text-gray-100 flex justify-center min-h-screen"
    x-data="{
        touchStartX: 0,
        touchEndX: 0,
        routes: [
            { name: 'dashboard', url: '{{ route('dashboard') }}' },
            { name: 'requests', url: '{{ route('requests') }}' },
            { name: 'sessions', url: '{{ route('sessions') }}' },
            { name: 'profile', url: '{{ route('profile') }}' },
        ],
        currentRoute: '{{ request()->route()->getName() }}',

        handleSwipe() {
            const distance = this.touchStartX - this.touchEndX;
            if (Math.abs(distance) < 70) return;

            const idx = this.routes.findIndex(r => r.name === this.currentRoute);
            if (idx === -1) return;

            let targetIdx;
            if (distance > 0) {
                // Swiped left → next page (RTL: forward)
                targetIdx = idx + 1;
            } else {
                // Swiped right → prev page (RTL: back)
                targetIdx = idx - 1;
            }

            if (targetIdx < 0 || targetIdx >= this.routes.length) return;

            const target = this.routes[targetIdx];
            if (window.Livewire && typeof window.Livewire.navigate === 'function') {
                window.Livewire.navigate(target.url);
            } else {
                window.location.href = target.url;
            }
        }
    }" @touchstart.passive="touchStartX = $event.changedTouches[0].screenX"
    @touchend="touchEndX = $event.changedTouches[0].screenX; handleSwipe()">

    <!-- Mobile App Frame -->
    <div
        class="w-full max-w-md bg-white dark:bg-[#1c1c1e] min-h-screen relative overflow-hidden shadow-2xl flex flex-col transition-colors duration-300">

        <!-- Header -->
        <header
            class="sticky top-0 z-50 w-full bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-2xl border-b border-black/5 dark:border-white/10 px-5 py-4 flex justify-between items-center transition-colors duration-300">
            <div class="font-bold text-xl tracking-tight">GymZ</div>
            <div class="flex items-center gap-3">
                <!-- Theme Toggle -->
                <button x-data="{
                    isDark: document.documentElement.classList.contains('dark'),
                    toggle() {
                        this.isDark = !this.isDark;
                        document.documentElement.classList.toggle('dark', this.isDark);
                        localStorage.setItem('darkMode', this.isDark);
                    }
                }" @click="toggle()"
                    class="p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-all duration-200 active:scale-90">
                    <svg x-show="isDark" x-cloak class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="!isDark" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <!-- Notifications -->
                <button
                    class="p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-all duration-200 active:scale-90 relative">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span
                        class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-[#1c1c1e]"></span>
                </button>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 pb-28 pt-4 px-4">
            {{ $slot }}
        </main>

        <!-- Bottom Navigation -->
        <x-bottom-nav />
    </div>

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>
</body>

</html>
