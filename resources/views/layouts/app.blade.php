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
    class="font-tajawal antialiased bg-gray-100 dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 flex justify-center min-h-screen">

    <!-- Strict Mobile Container (Phone Frame) -->
    <div
        class="w-full max-w-md bg-white/50 dark:bg-[#121212]/80 min-h-screen relative overflow-x-hidden shadow-2xl ring-1 ring-gray-900/5 dark:ring-white/10 flex flex-col">

        <!-- Liquid Glass Animated Blobs -->
        <div class="fixed inset-0 w-full max-w-md mx-auto pointer-events-none overflow-hidden z-0">
            <div
                class="absolute top-[-10%] left-[-10%] w-72 h-72 bg-blue-400/30 dark:bg-blue-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[80px] opacity-70 animate-blob">
            </div>
            <div
                class="absolute top-[20%] right-[-10%] w-72 h-72 bg-cyan-400/30 dark:bg-cyan-500/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[80px] opacity-70 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute bottom-[-10%] left-[20%] w-80 h-80 bg-purple-400/30 dark:bg-purple-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[80px] opacity-70 animate-blob animation-delay-4000">
            </div>
        </div>

        <!-- Sticky Glassmorphic Top Bar -->
        <header
            class="sticky top-0 z-50 w-full bg-white/40 dark:bg-[#1a1a1a]/40 backdrop-blur-2xl border-b border-white/20 dark:border-white/10 px-6 py-4 flex justify-between items-center">
            <div class="font-bold text-xl tracking-tight">GymZ</div>
            <button class="active:scale-90 transition-transform duration-300 relative">
                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <span
                    class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 border-2 border-white dark:border-gray-900"></span>
            </button>
        </header>

        <!-- Page Content -->
        <main class="relative z-10 flex-1 pb-28 pt-6 px-4">
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
