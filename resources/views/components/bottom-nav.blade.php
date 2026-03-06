@php
    $navItems = [
        [
            'route' => 'dashboard',
            'label' => 'الرئيسية',
            'icon' =>
                'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25', // Home icon
        ],
        [
            'route' => 'requests',
            'label' => 'الطلبات',
            'icon' =>
                'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0', // Bell icon
        ],
        [
            'route' => 'sessions',
            'label' => 'التمارين',
            'icon' => 'm3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z', // Bolt/Lightning icon
        ],
        [
            'route' => 'profile',
            'label' => 'البروفايل',
            'icon' =>
                'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z', // User icon
        ],
    ];
@endphp

<nav x-data="dockPill()" @touchstart="touchStart" @touchmove="touchMove" @touchend="touchEnd"
    :class="$store.nav.hidden ? 'opacity-0 translate-y-24 pointer-events-none' : 'opacity-100 translate-y-0'"
    class="fixed bottom-[calc(2rem+env(safe-area-inset-bottom))] left-1/2 -translate-x-1/2 flex items-center p-1.5 rounded-[2rem] bg-white/60 dark:bg-[#1c1c1e]/60 backdrop-blur-md backdrop-saturate-150 border border-white/50 dark:border-white/10 shadow-[0_8px_30px_rgba(0,0,0,0.15),inset_0_1px_0_rgba(255,255,255,0.9)] dark:shadow-[0_8px_30px_rgba(0,0,0,0.6),inset_0_1px_0_rgba(255,255,255,0.08)] z-50 w-[90%] max-w-[360px] transform-gpu touch-none transition-all duration-300 ease-out">

    <div
        class="absolute inset-0 rounded-[2rem] pointer-events-none bg-gradient-to-b from-white/70 via-white/20 to-white/5 dark:from-white/10 dark:via-white/5 dark:to-transparent">
    </div>

    <div id="active-pill" x-ref="pill"
        class="absolute top-1.5 bottom-1.5 right-0 bg-white/40 dark:bg-white/10 backdrop-blur-md rounded-full pointer-events-none"
        style="will-change: transform, width;">
    </div>

    @foreach ($navItems as $item)
        @php $isActive = request()->routeIs($item['route']); @endphp

        <a href="{{ route($item['route']) }}" wire:navigate data-route="{{ $item['route'] }}"
            data-active="{{ $isActive ? 'true' : 'false' }}"
            class="flex-1 flex flex-col items-center justify-center py-2 relative z-10 transition-colors duration-200
            {{ $isActive ? 'text-gymz-accent' : 'text-gray-500 dark:text-gray-400' }}">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="{{ $isActive ? '2' : '1.5' }}" stroke="currentColor"
                class="w-[22px] h-[22px] transition-transform duration-200 {{ $isActive ? 'scale-110' : 'scale-100' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
            </svg>

            <span class="text-[10px] font-semibold mt-0.5">
                {{ $item['label'] }}
            </span>
        </a>
    @endforeach
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dockPill', () => ({
            tabs: [],
            pill: null,
            isDragging: false,
            startX: 0,
            currentTranslateX: 0,
            initialTranslateX: 0,

            init() {
                this.tabs = Array.from(this.$el.querySelectorAll('a[data-route]'));
                this.pill = this.$refs.pill;

                setTimeout(() => {
                    const activeTab = this.tabs.find(t => t.dataset.active === 'true') ||
                        this.tabs[0];
                    this.snapTo(activeTab, true);
                }, 50);

                window.addEventListener('resize', () => {
                    const activeTab = this.tabs.find(t => t.dataset.active === 'true') ||
                        this.tabs[0];
                    this.snapTo(activeTab, true);
                });

                this.tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        this.snapTo(tab, false);
                    });
                });
            },

            snapTo(targetEl, instant = false) {
                if (!targetEl || !this.pill) return;
                const parentRect = this.$el.getBoundingClientRect();
                const elRect = targetEl.getBoundingClientRect();

                if (instant) {
                    this.pill.style.transition = 'none';
                } else {
                    this.pill.style.transition =
                        'transform 0.4s cubic-bezier(0.32,0.72,0,1), width 0.4s cubic-bezier(0.32,0.72,0,1)';
                }

                this.pill.style.width = `${elRect.width}px`;

                const offset = parentRect.right - elRect.right;
                this.currentTranslateX = -offset;
                this.initialTranslateX = -offset;

                this.pill.style.transform = `translateX(${this.currentTranslateX}px)`;
            },

            touchStart(e) {
                this.isDragging = true;
                this.startX = e.touches[0].clientX;
                this.pill.style.transition = 'none';
            },

            touchMove(e) {
                if (!this.isDragging) return;
                const deltaX = e.touches[0].clientX - this.startX;
                this.currentTranslateX = this.initialTranslateX + deltaX;

                const padding = 6;
                const maxRight = -padding;
                const maxLeft = -(this.$el.getBoundingClientRect().width - this.tabs[this.tabs
                    .length - 1].getBoundingClientRect().width - padding);
                this.currentTranslateX = Math.min(maxRight, Math.max(maxLeft, this
                    .currentTranslateX));

                this.pill.style.transform = `translateX(${this.currentTranslateX}px)`;
            },

            touchEnd(e) {
                if (!this.isDragging) return;
                this.isDragging = false;

                const pillRect = this.pill.getBoundingClientRect();
                const pillCenter = pillRect.left + (pillRect.width / 2);

                let closestTab = this.tabs[0];
                let minDistance = Infinity;

                this.tabs.forEach((tab) => {
                    const tabRect = tab.getBoundingClientRect();
                    const tabCenter = tabRect.left + (tabRect.width / 2);
                    const distance = Math.abs(tabCenter - pillCenter);
                    if (distance < minDistance) {
                        minDistance = distance;
                        closestTab = tab;
                    }
                });

                this.snapTo(closestTab, false);

                if (closestTab.dataset.active !== 'true') {
                    setTimeout(() => {
                        window.Livewire.navigate(closestTab.getAttribute('href'));
                    }, 150);
                }
            }
        }));
    });
</script>
