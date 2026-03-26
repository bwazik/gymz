<div>
    {{-- Leaflet CDN Assets --}}
    @assets
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <style>
            #explore-map-container {
                position: absolute;
                inset: 0;
                z-index: 1;
            }

            .leaflet-control-attribution {
                font-size: 9px !important;
            }

            /* Override Leaflet's LTR internals so map panning works in RTL pages */
            .leaflet-container {
                direction: ltr;
            }

            .gymz-tooltip {
                font-family: 'Tajawal', sans-serif !important;
                font-size: 13px !important;
                font-weight: 700 !important;
                direction: rtl;
                padding: 6px 12px !important;
                border-radius: 12px !important;
                background: rgba(255, 255, 255, 0.85) !important;
                backdrop-filter: blur(12px);
                border: 1px solid rgba(0, 0, 0, 0.05) !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
                color: #1c1c1e !important;
            }

            .dark .gymz-tooltip {
                background: rgba(28, 28, 30, 0.9) !important;
                border-color: rgba(255, 255, 255, 0.1) !important;
                color: #f2f2f7 !important;
            }

            @keyframes marker-pulse {
                0% {
                    transform: translate(-50%, -50%) scale(1);
                    opacity: 0.7;
                }

                70% {
                    transform: translate(-50%, -50%) scale(2.2);
                    opacity: 0;
                }

                100% {
                    transform: translate(-50%, -50%) scale(2.2);
                    opacity: 0;
                }
            }

            .user-location-dot {
                width: 16px;
                height: 16px;
                background: #007AFF;
                border: 3px solid white;
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(0, 122, 255, 0.5);
            }

            .hide-scrollbar {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
        </style>
    @endassets

    {{-- Full-screen Map Container --}}
    <div class="-mx-4 -mt-6" style="height: calc(100vh - 0px); position: relative;">

        <div id="explore-map-container" x-data="exploreMap({{ json_encode($gymsMapData) }}, {{ json_encode($mapCenter) }}, {{ json_encode($userPhotoUrl) }})" wire:ignore>
        </div>

        {{-- Sport Category Toggles --}}
        <div class="absolute top-14 left-1/2 -translate-x-1/2 z-[30]" x-data="{ panelOpen: @entangle('showGymPanel').live }" x-show="!panelOpen"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div
                class="flex gap-1.5 bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-xl rounded-2xl p-1.5 border border-black/5 dark:border-white/10 shadow-lg">
                <button type="button" wire:click="filterCategory(null)"
                    class="px-4 py-1.5 rounded-xl text-[11px] font-bold transition-all active:scale-95
                    {{ $selectedCategoryId === null ? 'bg-gymz-accent text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/10' }}">
                    الكل
                </button>
                @foreach ($categories as $category)
                    <button type="button" wire:click="filterCategory({{ $category->id }})"
                        class="px-4 py-1.5 rounded-xl text-[11px] font-bold transition-all active:scale-95 whitespace-nowrap
                        {{ $selectedCategoryId === $category->id ? 'bg-gymz-accent text-white shadow-md' : 'text-gray-600 dark:text-gray-300 hover:bg-black/5 dark:hover:bg-white/10' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Map Legend --}}
        <div class="absolute top-28 right-4 z-[30]" x-data="{ panelOpen: @entangle('showGymPanel').live }" x-show="!panelOpen"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div
                class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-xl rounded-2xl p-3 border border-black/5 dark:border-white/10 shadow-lg">
                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 tracking-wider">نشاط الجيمات</p>
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                        <span class="text-[10px] text-gray-600 dark:text-gray-300">لا يوجد تمارين</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] text-gray-600 dark:text-gray-300">١-٢ تمارين</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="text-[10px] text-gray-600 dark:text-gray-300">٣-٥ تمارين</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-[10px] text-gray-600 dark:text-gray-300">٦+ تمارين 🔥</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Locate Me Button --}}
        <div class="absolute bottom-[calc(9.5rem+env(safe-area-inset-bottom))] left-4 z-40"
            style="bottom: calc(9.5rem + env(safe-area-inset-bottom));">
            <button type="button" x-data @click="$dispatch('locate-user')"
                class="w-11 h-11 rounded-full bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-xl border border-black/5 dark:border-white/10 shadow-lg flex items-center justify-center active:scale-90 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5 text-[#007AFF]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12m-3 0a3 3 0 106 0 3 3 0 10-6 0" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3m0 14v3M2 12h3m14 0h3" />
                </svg>
            </button>
        </div>

    </div>

    {{-- Bottom Sheet Panel for Gym Intents --}}
    <div x-data="{ open: @entangle('showGymPanel').live }" x-show="open" x-init="$watch('open', val => $dispatch(val ? 'hide-bottom-nav' : 'show-bottom-nav'))" style="display: none;"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/40 backdrop-blur-md flex items-end justify-center"
        @click.self="$wire.closePanel()">

        {{-- Sheet Body --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-out duration-300" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 p-6 rounded-t-[2rem] w-full max-w-md shadow-2xl pb-[calc(1.5rem+env(safe-area-inset-bottom))] flex flex-col max-h-[80vh]"
            @click.stop>

            {{-- Drag Handle --}}
            <div class="flex justify-center mb-4 shrink-0">
                <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5 shrink-0">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $selectedGym?->name ?? 'جيم' }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        تمارين الـ ٢٤ ساعة الجاية
                    </p>
                </div>
                <button type="button" wire:click="closePanel"
                    class="p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-all active:scale-90">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Intent Cards List --}}
            <div class="flex-1 min-h-0 overflow-y-auto pb-4 hide-scrollbar">
                @forelse ($selectedGymIntents as $intent)
                    @include('partials.intent-card', [
                        'intent' => $intent,
                        'sentRequestIntentIds' => $sentRequestIntentIds,
                    ])
                @empty
                    <x-glass-card class="p-8 text-center">
                        <div
                            class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="w-8 h-8 text-gray-400 dark:text-white/30">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                            </svg>
                        </div>
                        <h3 class="text-gray-700 dark:text-white/70 font-medium mb-1">مفيش تمارين في الجيم ده دلوقتي
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">ارجع تاني بعدين أو نزل تمرينتك! 💪</p>
                    </x-glass-card>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Alpine Map Logic --}}
    @script
        <script>
            Alpine.data('exploreMap', (initialGyms, initialCenter, userPhoto) => ({
                gymsData: initialGyms,
                mapCenter: initialCenter,
                userPhotoUrl: userPhoto,
                map: null,
                markers: [],
                userMarker: null,

                init() {
                    this.$nextTick(() => this.initMap());

                    let refreshTimer = null;
                    this.$wire.on('gyms-updated', (data) => {
                        clearTimeout(refreshTimer);
                        refreshTimer = setTimeout(() => this.refreshMarkers(data.gyms), 50);
                    });

                    window.addEventListener('locate-user', () => this.locateUser());

                    window.addEventListener('fly-to-city', (e) => {
                        if (!this.map) return;
                        const {
                            lat,
                            lng
                        } = e.detail;
                        if (lat && lng) {
                            setTimeout(() => {
                                this.markers.forEach(m => {
                                    if (m.getTooltip()) m.closeTooltip();
                                });
                                this.map.flyTo([lat, lng], 13);
                            }, 200);
                        }
                    });
                },

                initMap() {
                    const isDark = document.documentElement.classList.contains('dark');

                    this.map = L.map(this.$el, {
                        center: [this.mapCenter.lat, this.mapCenter.lng],
                        zoom: 13,
                        zoomControl: false,
                        zoomAnimation: true,
                        attributionControl: true,
                    });

                    const lightTiles = L.tileLayer(
                        'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
                            subdomains: 'abcd',
                            maxZoom: 19,
                        });

                    const darkTiles = L.tileLayer(
                        'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>',
                            subdomains: 'abcd',
                            maxZoom: 19,
                        });

                    (isDark ? darkTiles : lightTiles).addTo(this.map);

                    const observer = new MutationObserver(() => {
                        const nowDark = document.documentElement.classList.contains('dark');
                        if (nowDark && this.map.hasLayer(lightTiles)) {
                            this.map.stop();
                            this.markers.forEach(m => {
                                if (m.getTooltip()) m.closeTooltip();
                            });
                            this.map.removeLayer(lightTiles);
                            darkTiles.addTo(this.map);
                        } else if (!nowDark && this.map.hasLayer(darkTiles)) {
                            this.map.stop();
                            this.markers.forEach(m => {
                                if (m.getTooltip()) m.closeTooltip();
                            });
                            this.map.removeLayer(darkTiles);
                            lightTiles.addTo(this.map);
                        }
                    });
                    observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });

                    L.control.zoom({
                        position: 'bottomleft'
                    }).addTo(this.map);

                    this.placeMarkers(this.gymsData);

                    if (this.gymsData.length > 0) {
                        const bounds = L.latLngBounds(this.gymsData.map(g => [g.lat, g.lng]));
                        this.map.fitBounds(bounds, {
                            padding: [50, 50],
                            maxZoom: 15
                        });
                    }

                    this.map.on('locationfound', (e) => {
                        if (this.userMarker) this.map.removeLayer(this.userMarker);

                        const markerSize = 40;
                        const containerSize = markerSize +
                            20; // match gym marker pattern: 10px overflow each side
                        const offset = containerSize / 2;

                        const icon = L.divIcon({
                            html: `
                                <div style="
                                    position:relative;
                                    width:${containerSize}px; height:${containerSize}px;
                                    display:flex; align-items:center; justify-content:center;
                                ">
                                    <div style="
                                        position:absolute; top:50%; left:50%;
                                        transform:translate(-50%,-50%);
                                        width:${markerSize + 10}px; height:${markerSize + 10}px;
                                        border-radius:50%; background:rgba(59,130,246,0.3);
                                        animation:marker-pulse 2s ease-out infinite;
                                        pointer-events:none; z-index:-1;
                                    "></div>
                                    <div style="
                                        width:${markerSize}px; height:${markerSize}px;
                                        border-radius:50%; border:3px solid #3b82f6;
                                        background:white; box-shadow:0 2px 8px rgba(0,0,0,0.25);
                                        overflow:hidden; display:flex; align-items:center; justify-content:center;
                                    ">
                                        <img src="${this.userPhotoUrl}" alt="موقعك"
                                            style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />
                                    </div>
                                </div>
                            `,
                            className: '',
                            iconSize: [containerSize, containerSize],
                            iconAnchor: [offset, offset],
                        });

                        this.userMarker = L.marker(e.latlng, {
                            icon
                        }).addTo(this.map);
                        this.userMarker.bindTooltip('موقعك', {
                            direction: 'top',
                            offset: [0, -(containerSize / 2)],
                            className: 'gymz-tooltip',
                        });
                    });

                    this.map.on('locationerror', () => {});

                    // Close all tooltips before zoom/pan to prevent Leaflet's internal null-map crash
                    this.map.on('zoomstart movestart', () => {
                        this.markers.forEach(m => {
                            if (m.getTooltip()) m.closeTooltip();
                        });
                        if (this.userMarker?.getTooltip()) this.userMarker.closeTooltip();
                    });

                    // Patch L.Tooltip.prototype to guard against null map during zoom animation.
                    // This covers the race condition where a tooltip is unbound mid-zoom and
                    // Leaflet's requestAnimationFrame callback still fires _animateZoom/_updatePosition.
                    if (!L.Tooltip.prototype._gymzPatched) {
                        // Tooltip null-map guard
                        const tProto = L.Tooltip.prototype;
                        const origUpdate = tProto._updatePosition;
                        tProto._updatePosition = function() {
                            if (!this._map) return;
                            origUpdate.call(this);
                        };
                        const origAnimate = tProto._animateZoom;
                        tProto._animateZoom = function(e) {
                            if (!this._map) return;
                            origAnimate.call(this, e);
                        };
                        tProto._gymzPatched = true;
                    }

                    if (!L.GridLayer.prototype._gymzPatched2) {
                        const gProto = L.GridLayer.prototype;

                        const origSetView = gProto._setView;
                        gProto._setView = function(center, zoom, noPrune, noUpdate) {
                            if (!this._map) return;
                            origSetView.call(this, center, zoom, noPrune, noUpdate);
                        };

                        const origResetView = gProto._resetView;
                        gProto._resetView = function() {
                            if (!this._map) return;
                            origResetView.call(this);
                        };

                        const origGridAnimate = gProto._animateZoom;
                        gProto._animateZoom = function(e) {
                            if (!this._map) return;
                            origGridAnimate.call(this, e);
                        };

                        gProto._gymzPatched2 = true;
                    }
                },

                refreshMarkers(newData) {
                    this.markers.forEach(m => {
                        try {
                            const tt = m.getTooltip();
                            if (tt) {
                                if (tt._map) tt._map.removeLayer(tt);
                                m.unbindTooltip();
                            }
                            this.map.removeLayer(m);
                        } catch (e) {}
                    });

                    this.markers = [];
                    this.placeMarkers(newData);
                },

                locateUser() {
                    this.markers.forEach(m => {
                        if (m.getTooltip()) m.closeTooltip();
                    });
                    this.map.locate({
                        setView: true,
                        maxZoom: 15
                    });
                },

                placeMarkers(gyms) {
                    gyms.forEach(gym => {
                        const color = this.getHeatColor(gym.count);
                        const size = this.getSize(gym.count);
                        const isHot = gym.count >= 5;

                        const containerSize = size + 20;
                        const offset = containerSize / 2;

                        const pulseHtml = isHot ? `
                            <div style="
                                position:absolute; top:50%; left:50%;
                                transform:translate(-50%,-50%);
                                width:${size + 10}px; height:${size + 10}px;
                                border-radius:50%; background:${color};
                                animation:marker-pulse 2s ease-out infinite;
                                pointer-events:none; z-index:-1;
                            "></div>` : '';

                        const innerHtml = gym.logo ? `
                            <div style="
                                width:${size}px; height:${size}px; border-radius:50%;
                                border:3px solid ${color}; background:white;
                                box-shadow:0 2px 8px rgba(0,0,0,0.25);
                                overflow:hidden; display:flex; align-items:center; justify-content:center;
                            ">
                                <img src="${gym.logo}" alt="${gym.name}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:50%;" />
                            </div>` : `
                            <div style="
                                width:${size}px; height:${size}px; border-radius:50%;
                                border:3px solid ${color}; background:white;
                                box-shadow:0 2px 8px rgba(0,0,0,0.25);
                                display:flex; align-items:center; justify-content:center;
                            ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="${color}" style="width:60%;height:60%;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                            </div>`;

                        const badgeHtml = gym.count > 0 ? `
                            <span style="
                                position:absolute; top:6px; right:6px;
                                min-width:18px; height:18px; border-radius:9px;
                                font-size:10px; font-weight:700; color:white; background:${color};
                                display:flex; align-items:center; justify-content:center;
                                padding:0 4px; box-shadow:0 1px 3px rgba(0,0,0,0.3);
                                border:2px solid white; line-height:1;
                            ">${gym.count}</span>` : '';

                        const html = `
                            <div style="
                                position:relative;
                                width:${containerSize}px; height:${containerSize}px;
                                display:flex; align-items:center; justify-content:center;
                            ">
                                ${pulseHtml}${innerHtml}${badgeHtml}
                            </div>`;

                        const icon = L.divIcon({
                            html,
                            className: '',
                            iconSize: [containerSize, containerSize],
                            iconAnchor: [offset, offset],
                        });

                        const marker = L.marker([gym.lat, gym.lng], {
                            icon
                        }).addTo(this.map);

                        const wordForm = gym.count > 2 ? 'تمارين' : 'تمرين';
                        const label = gym.count > 0 ? `${gym.name} — ${gym.count} ${wordForm}` : gym.name;

                        marker.bindTooltip(label, {
                            direction: 'top',
                            offset: [0, -(containerSize / 2)],
                            className: 'gymz-tooltip',
                        });

                        marker.on('mouseout', () => {
                            try {
                                marker.closeTooltip();
                            } catch (e) {}
                        });
                        marker.on('click', () => this.$wire.selectGym(gym.id));

                        this.markers.push(marker);
                    });
                },

                getHeatColor(count) {
                    if (count === 0) return '#9ca3af';
                    if (count <= 2) return '#10b981';
                    if (count <= 5) return '#f59e0b';
                    return '#ef4444';
                },

                getSize(count) {
                    if (count === 0) return 36;
                    if (count <= 2) return 40;
                    if (count <= 5) return 44;
                    return 48;
                },
            }));
        </script>
    @endscript
</div>
