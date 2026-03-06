<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f3f4f6" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0a0a0a" media="(prefers-color-scheme: dark)">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="GymZ">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <title>{{ config('app.name', 'GymZ') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-tajawal antialiased bg-[#e9ecef] dark:bg-[#050505] text-gray-900 dark:text-gray-100 flex justify-center min-h-screen selection:bg-gray-300 dark:selection:bg-white/20"
    x-data="{
        rotateX: 0,
        rotateY: 0,
        isDragging: false,
        startX: 0,
        startY: 0,
        start(e) {
            this.isDragging = true;
            this.startX = e.clientX || (e.touches && e.touches[0].clientX) || 0;
            this.startY = e.clientY || (e.touches && e.touches[0].clientY) || 0;
        },
        move(e) {
            if (!this.isDragging) return;
            let clientX = e.clientX || (e.touches && e.touches[0].clientX) || 0;
            let clientY = e.clientY || (e.touches && e.touches[0].clientY) || 0;
            let deltaX = clientX - this.startX;
            let deltaY = clientY - this.startY;
            this.rotateY = Math.max(-20, Math.min(20, deltaX * 0.1));
            this.rotateX = Math.max(-20, Math.min(20, -deltaY * 0.1));
        },
        end() {
            this.isDragging = false;
            this.rotateX = 0;
            this.rotateY = 0;
        }
    }"
    @mousedown.window="start($event)"
    @touchstart.window="start($event)"
    @mousemove.window="move($event)"
    @touchmove.window="move($event)"
    @mouseup.window="end()"
    @touchend.window="end()"
    @mouseleave.window="end()"
>

    <!-- Strict Mobile Container (Phone Frame) -->
    <div
        class="w-full max-w-md bg-[#fafafa] dark:bg-[#121212] min-h-screen relative overflow-x-hidden shadow-[0_0_50px_rgba(0,0,0,0.1)] dark:shadow-[0_0_50px_rgba(0,0,0,0.5)] sm:border-x border-gray-200/50 dark:border-white/5 flex flex-col">

        <!-- Sticky Glassmorphic Top Bar -->
        <header
            class="sticky top-0 z-50 w-full bg-[#fafafa]/70 dark:bg-[#121212]/70 backdrop-blur-2xl border-b border-black/5 dark:border-white/5 px-6 py-4 flex justify-between items-center">
            <div class="font-bold text-xl tracking-tight text-gray-900 dark:text-white">GymZ</div>
            <button class="active:scale-95 transition-transform duration-300 relative group">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <span
                    class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
            </button>
        </header>

        <!-- Page Content -->
        <main class="relative z-10 flex-1 pb-32 pt-6 px-4 snap-y snap-mandatory overflow-y-auto">
            {{ $slot }}
        </main>

        <!-- Bottom Navigation Component -->
        <x-bottom-nav />

    </div>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
</body>

</html>
