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
    class="bg-[#f2f2f7] dark:bg-[#000000] font-tajawal antialiased text-gray-900 dark:text-gray-100 flex justify-center min-h-screen selection:bg-blue-500/30">

    <main class="w-full max-w-md mx-auto relative min-h-screen pb-32 pt-6 px-4 shadow-2xl bg-white/30 dark:bg-white/5"
        x-data="swipeNavigator()" @touchstart="touchStart" @touchend="touchEnd">
        {{ $slot }}
    </main>

    <!-- Bottom Navigation -->
    <x-bottom-nav />

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>

    <!-- Swipe Navigator -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('swipeNavigator', () => ({
                startX: 0,
                init() {
                    const activeTab = document.querySelector('[data-active="true"]');
                    if (activeTab) this.movePill(activeTab);
                },
                movePill(element) {
                    const pill = document.getElementById('active-pill');
                    if (!pill || !element) return;
                    const parentRect = element.parentElement.getBoundingClientRect();
                    const elRect = element.getBoundingClientRect();
                    // RTL calculation
                    pill.style.transform = `translateX(${-(parentRect.right - elRect.right - 6)}px)`;
                },
                touchStart(e) {
                    this.startX = e.touches[0].clientX;
                },
                touchEnd(e) {
                    const endX = e.changedTouches[0].clientX;
                    const diff = this.startX - endX;
                    if (Math.abs(diff) > 75) {
                        const routes = ['dashboard', 'requests', 'sessions', 'profile'];
                        let currentIdx = routes.indexOf('{{ request()->route()->getName() }}');
                        if (currentIdx === -1) return;

                        let nextIdx = diff < 0 ? currentIdx - 1 : currentIdx + 1; // RTL logic
                        if (nextIdx >= 0 && nextIdx < routes.length) {
                            const targetEl = document.querySelector(
                            `[data-route="${routes[nextIdx]}"]`);
                            this.movePill(targetEl);
                            window.Livewire.navigate('/' + routes[nextIdx]);
                        }
                    }
                }
            }));
        });
    </script>
</body>

</html>
