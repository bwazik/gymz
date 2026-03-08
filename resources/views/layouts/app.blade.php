<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="#f2f2f7" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">

    <title>{{ config('app.name', 'GymZ') }} | رفيق تمرينك</title>
    <meta name="description"
        content="GymZ هو التطبيق الرياضي الأول في مصر للبحث عن رفقاء التمرين، تتبع إنجازاتك، وكسب مكافآت. Find your perfect gym partner, track workouts, and earn rewards with GymZ.">
    <meta name="keywords"
        content="Gym, Fitness, Workout partner, Egypt gym, GymZ, كمال أجسام, جيم, تمرين, رفيق تمرين, جيمز, لياقة بدنية, فورمة">
    <meta name="author" content="GymZ Team">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="GymZ - Find Your Spotter">
    <meta property="og:description"
        content="التطبيق الرياضي الأول في مصر للبحث عن رفقاء التمرين وكسب المكافآت. انضم للوحوش دلوقتي!">
    <meta property="og:image" content="{{ asset('icons/og-image.png') }}">
    <meta property="og:locale" content="ar_EG">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="GymZ - Find Your Spotter">
    <meta name="twitter:description"
        content="التطبيق الرياضي الأول في مصر للبحث عن رفقاء التمرين وكسب المكافآت. انضم للوحوش دلوقتي!">
    <meta name="twitter:image" content="{{ asset('icons/og-image.png') }}">

    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="GymZ" />

    <link rel="icon" type="image/png" href="{{ asset('icons/favicon.png/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('icons/favicon.png/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('icons/favicon.png/favicon.ico') }}" />
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">

    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@15.2.1/dist/css/shepherd.css" />
    <style>
        .shepherd-element {
            background: transparent !important;
            font-family: inherit !important;
            border: none !important;
            border-radius: 2rem !important;
            box-shadow: none !important;
        }

        .shepherd-has-title .shepherd-content .shepherd-header {
            background: transparent !important;
            padding: 1.5rem 1.5rem 0.5rem !important;
        }

        .shepherd-text {
            padding: 0 1.5rem 1.25rem !important;
            line-height: 1.6 !important;
        }

        .shepherd-footer {
            padding: 0 1.5rem 1.5rem !important;
            border-bottom-left-radius: 2rem !important;
            border-bottom-right-radius: 2rem !important;
        }

        .shepherd-title {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: #111827 !important;
            /* gray-900 */
        }

        .dark .shepherd-title {
            color: #ffffff !important;
        }

        .shepherd-arrow,
        .shepherd-arrow:before {
            display: none !important;
        }

        /* Override Shepherd Default Button Styles */
        .shepherd-button {
            background-color: #ff2d55 !important;
            color: #ffffff !important;
            transition: all 0.2s ease !important;
        }

        .shepherd-button:hover,
        .shepherd-button:focus {
            background-color: #ff2d55 !important;
            color: #ffffff !important;
            opacity: 1 !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-[#f2f2f7] dark:bg-[#000000] font-tajawal antialiased text-gray-900 dark:text-gray-100 flex justify-center min-h-screen selection:bg-blue-500/30">

    <x-toaster />
    <x-ios-alert />
    <livewire:public-trust-profile />

    <!-- PWA Install Modal -->
    <div x-data="pwaPrompt">
        {{-- Modal Overlay --}}
        <div x-data="{ open: false }" x-on:open-pwa-modal.window="open = true; $dispatch('hide-bottom-nav')"
            x-on:close-pwa-modal.window="open = false; $dispatch('show-bottom-nav')" x-show="open"
            style="display: none;" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-out duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[60] bg-black/40 backdrop-blur-md flex items-end justify-center"
            @click.self="dismissPwa()">

            {{-- Bottom Sheet Modal --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-out duration-300" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 rounded-t-[2rem] w-full max-w-md shadow-2xl relative"
                @click.stop>

                {{-- Drag Handle --}}
                <div class="flex justify-center pt-4 pb-2">
                    <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
                </div>

                <div class="p-6 pt-2 pb-[calc(1.5rem+env(safe-area-inset-bottom))] text-center">
                    <img src="{{ asset('icons/icon-192x192.png') }}" alt="GymZ Icon"
                        class="w-20 h-20 mx-auto rounded-3xl shadow-xl border border-black/5 dark:border-white/10 mb-5">
                    <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-2">نزل ابلكيشن GymZ 📱</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed px-2">
                        تجربة أسرع، إشعارات حية، ومساحة أقل من 1 ميجا!
                    </p>
                    <div class="flex flex-col gap-3">
                        <button @click="installPwa()"
                            class="w-full py-3.5 rounded-2xl bg-gymz-accent text-white font-bold text-sm active:scale-95 transition-all shadow-md">
                            تثبيت
                        </button>
                        <button @click="dismissPwa()"
                            class="w-full py-3.5 rounded-2xl bg-black/5 dark:bg-white/10 text-gray-700 dark:text-white/70 font-bold text-sm active:scale-95 transition-all">
                            ليس الآن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="w-full max-w-md mx-auto relative min-h-screen pb-32 pt-6 px-4 shadow-2xl bg-white/30 dark:bg-white/5">
        <!-- Header -->
        <header
            class="sticky top-0 z-40 -mx-4 -mt-6 px-5 py-2.5 flex justify-between items-center bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-2xl border-b border-black/5 dark:border-white/10 transition-colors duration-300">
            <div class="font-black text-2xl tracking-tighter drop-shadow-sm">Gym<span
                    class="text-gymz-accent">Z</span>
            </div>
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

        {{ $slot }}
    </main>

    <!-- Bottom Navigation -->
    <x-bottom-nav />

    {{-- Session Toasts --}}
    @if (session()->has('toast'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: @json(session('toast'))
                    }));
                }, 100);
            });
        </script>
    @endif

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
        }
    </script>

    <script>
        function applyDarkMode() {
            if (localStorage.getItem('darkMode') === 'true' ||
                (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        document.addEventListener('livewire:navigated', applyDarkMode);
    </script>

    <script>
        // Capture the PWA install prompt globally right away so we don't miss it
        window.deferredPwaPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            window.deferredPwaPrompt = e;
            window.dispatchEvent(new Event('pwa-ready'));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.store('nav', {
                hidden: false
            });

            Alpine.data('pwaPrompt', () => ({
                deferredPrompt: null,
                init() {
                    const handlePrompt = () => {
                        this.deferredPrompt = window.deferredPwaPrompt;
                        if (!this.deferredPrompt) return;

                        const dismissedAt = localStorage.getItem('gymz_pwa_dismissed');
                        const now = new Date().getTime();
                        const threeDays = 3 * 24 * 60 * 60 * 1000;

                        if (!dismissedAt || (now - dismissedAt > threeDays)) {
                            setTimeout(() => {
                                window.dispatchEvent(new CustomEvent('open-pwa-modal'));
                            }, 2000);
                        }
                    };

                    // Check immediately if it already fired
                    if (window.deferredPwaPrompt) {
                        handlePrompt();
                    } else {
                        // Otherwise wait for it
                        window.addEventListener('pwa-ready', handlePrompt);
                    }
                },
                installPwa() {
                    if (this.deferredPrompt) {
                        this.deferredPrompt.prompt();
                        this.deferredPrompt.userChoice.then((choiceResult) => {
                            if (choiceResult.outcome === 'accepted') {
                                console.log('User accepted the install prompt');
                            }
                            this.deferredPrompt = null;
                            window.dispatchEvent(new CustomEvent('close-pwa-modal'));
                        });
                    }
                },
                dismissPwa() {
                    localStorage.setItem('gymz_pwa_dismissed', new Date().getTime());
                    window.dispatchEvent(new CustomEvent('close-pwa-modal'));
                }
            }));
        });
        // Listen at the document level — these fire regardless of component re-renders
        document.addEventListener('hide-bottom-nav', () => {
            if (window.Alpine) Alpine.store('nav').hidden = true;
        });
        document.addEventListener('show-bottom-nav', () => {
            if (window.Alpine) Alpine.store('nav').hidden = false;
        });
    </script>

    <!-- Shepherd.js Tour -->
    <script type="module">
        import Shepherd from 'https://cdn.jsdelivr.net/npm/shepherd.js@15.2.1/dist/js/shepherd.mjs';

        document.addEventListener('DOMContentLoaded', () => {
            if (!localStorage.getItem('gymz_tour_completed')) {
                const tour = new Shepherd.Tour({
                    useModalOverlay: true,
                    defaultStepOptions: {
                        classes: 'bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 shadow-2xl text-gray-900 dark:text-white',
                        scrollTo: true,
                        cancelIcon: {
                            enabled: false
                        }
                    }
                });

                tour.addStep({
                    id: 'welcome',
                    title: 'أهلاً بيك في GymZ 🏋🏽',
                    text: '<p class="text-gray-500 dark:text-gray-400 text-sm">خلينا ناخد جولة سريعة في 10 ثواني عشان تعرف إزاي تستخدم الأبلكيشن.</p>',
                    attachTo: {
                        element: 'body',
                        on: 'center'
                    },
                    buttons: [{
                        text: 'يلا بينا',
                        classes: 'bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md',
                        action: tour.next
                    }]
                });

                tour.addStep({
                    id: 'create',
                    title: 'إضافة التمارين ⚡',
                    text: '<p class="text-gray-500 dark:text-gray-400 text-sm">من هنا تقدر تنزل تمرينتك وتتحدى وحوش الجيم ينزلوا معاك!</p>',
                    attachTo: {
                        element: '#tour-create-btn',
                        on: 'top'
                    },
                    buttons: [{
                        text: 'التالي',
                        classes: 'bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md',
                        action: tour.next
                    }]
                });

                tour.addStep({
                    id: 'feed',
                    title: 'تمارين منطقتك 🗺️',
                    text: '<p class="text-gray-500 dark:text-gray-400 text-sm">هنا بتظهر التمارين وتقدر تشوف تمرينات الأبطال التانيين وتنضم ليهم.</p>',
                    attachTo: {
                        element: '#tour-feed',
                        on: 'bottom'
                    },
                    buttons: [{
                        text: 'التالي',
                        classes: 'bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md',
                        action: tour.next
                    }]
                });

                tour.addStep({
                    id: 'requests',
                    title: 'طلبات التمرين 🤝',
                    text: '<p class="text-gray-500 dark:text-gray-400 text-sm">في الصفحة دي هتشوف طلبات التمرين اللي بعتها واللي اتبعتتلك عشان تقبلها أو ترفضها.</p>',
                    attachTo: {
                        element: '#tour-nav-requests',
                        on: 'top'
                    },
                    buttons: [{
                        text: 'التالي',
                        classes: 'bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md',
                        action: tour.next
                    }]
                });

                tour.addStep({
                    id: 'sessions',
                    title: 'تأكيد التمرين (QR) 📷',
                    text: '<p class="text-gray-500 dark:text-gray-400 text-sm">هنا بتتم التمرينة الفعلية! بتأكد حضورك بالكيو آر كود وتبدأ التمرينة عشان تكسب النقط.</p>',
                    attachTo: {
                        element: '#tour-nav-sessions',
                        on: 'top'
                    },
                    buttons: [{
                        text: 'التالي',
                        classes: 'bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md',
                        action: tour.next
                    }]
                });

                tour.addStep({
                    id: 'profile',
                    title: 'بروفايلك ومحفظتك 🍑',
                    text: '<p class="text-gray-500 dark:text-gray-400 text-sm">آخر صفحة فيها تاريخ تمارينك، نسبة الثقة، محفظة الجلوتس، المتجر، ورابط الإحالة بتاعك.</p>',
                    attachTo: {
                        element: '#tour-nav-profile',
                        on: 'top'
                    },
                    buttons: [{
                        text: 'ابدأ الرحلة 🚀',
                        classes: 'bg-gymz-accent text-white font-bold rounded-2xl py-3.5 mt-2 w-full transition-all active:scale-95 text-center flex justify-center shadow-md',
                        action: () => {
                            localStorage.setItem('gymz_tour_completed', 'true');
                            tour.complete();
                        }
                    }]
                });

                setTimeout(() => {
                    tour.start();
                }, 1000);
            }
        });
    </script>
</body>

</html>
