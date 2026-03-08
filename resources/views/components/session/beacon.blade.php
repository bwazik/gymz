@props(['session', 'isRoleA'])

@php
    $myStatus = $isRoleA ? $session->user_a_status : $session->user_b_status;
    $partnerStatus = $isRoleA ? $session->user_b_status : $session->user_a_status;

    $colors = [
        'bg-black' => 'أسود',
        'bg-white' => 'أبيض',
        'bg-red-500' => 'أحمر',
        'bg-blue-500' => 'أزرق',
        'bg-green-500' => 'أخضر',
        'bg-yellow-400' => 'أصفر',
        'bg-gray-500' => 'رصاصي',
    ];

    $locations = ['الاستقبال', 'منطقة الكارديو', 'الأوزان الحرة', 'الأجهزة', 'في الطريق'];
@endphp

<div class="mb-6 space-y-4">
    {{-- Partner's Beacon --}}
    <div class="bg-blue-500/5 dark:bg-blue-500/10 border border-blue-500/20 rounded-2xl p-4 relative overflow-hidden">
        {{-- Glass shine --}}
        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-400/20 blur-2xl rounded-full -mr-10 -mt-10"></div>

        <div class="flex items-start justify-between relative z-10">
            <div>
                <h3 class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-1 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    حالة الشريك
                </h3>
                @if ($partnerStatus)
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-1.5 mt-2">
                        يرتدي <span
                            class="w-3 h-3 rounded-full inline-block border border-gray-300 {{ $partnerStatus['color'] }}"></span>
                        وموجود في
                        <span
                            class="bg-blue-500/10 text-blue-600 px-2.5 py-0.5 rounded-full text-xs font-bold">{{ $partnerStatus['location'] }}</span>
                    </p>
                    <p class="text-[10px] text-gray-500 mt-1.5">حدثت منذ
                        {{ \Carbon\Carbon::parse($partnerStatus['updated_at'])->diffForHumans() }}</p>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium italic">شريكك لم يحدد مكانه بعد
                    </p>
                @endif
            </div>

            <button wire:click="$refresh"
                class="shrink-0 p-2 rounded-full bg-blue-500/10 text-blue-600 hover:bg-blue-500/20 transition-all active:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </button>
        </div>
    </div>

    {{-- My Beacon --}}
    <div x-data="{
        selectedColor: '{{ $myStatus['color'] ?? '' }}',
        selectedLocation: '{{ $myStatus['location'] ?? '' }}',
        isUpdating: false,
        update() {
            if (!this.selectedColor || !this.selectedLocation) return;
            this.isUpdating = true;
            $wire.updateBeacon({{ $session->id }}, this.selectedColor, this.selectedLocation).then(() => {
                this.isUpdating = false;
            });
        }
    }"
        class="bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10 rounded-2xl p-4">

        <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
            حالة المقابلة (حالتي)
        </h3>

        {{-- Color Picker --}}
        <div class="mb-4">
            <p class="text-[10px] uppercase font-bold text-gray-400 mb-2">لون التيشيرت</p>
            <div class="flex gap-2 flex-wrap">
                @foreach ($colors as $bg => $name)
                    <button type="button" @click="selectedColor = '{{ $bg }}'"
                        :class="selectedColor === '{{ $bg }}' ?
                            'ring-2 ring-offset-2 ring-gymz-accent dark:ring-offset-[#1c1c1e] scale-110' :
                            'opacity-70 grayscale-[50%]'"
                        class="w-8 h-8 rounded-full {{ $bg }} border border-gray-300 dark:border-gray-600 transition-all active:scale-95 shadow-sm"
                        title="{{ $name }}">
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Location Picker --}}
        <div class="mb-5">
            <p class="text-[10px] uppercase font-bold text-gray-400 mb-2">مكاني دلوقتي</p>
            <div class="flex gap-2 flex-wrap">
                @foreach ($locations as $loc)
                    <button type="button" @click="selectedLocation = '{{ $loc }}'"
                        :class="selectedLocation === '{{ $loc }}' ? 'bg-gymz-accent text-white border-transparent' :
                            'bg-white dark:bg-[#2c2c2e] text-gray-700 dark:text-gray-300 border-black/10 dark:border-white/10 hover:bg-black/5'"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold border transition-colors shadow-sm">
                        {{ $loc }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Update Button --}}
        <div class="mt-4">
            <x-ios-button @click="update()" x-bind:disabled="!selectedColor || !selectedLocation || isUpdating"
                x-bind:class="(!selectedColor || !selectedLocation) ? '!bg-black/5 dark:!bg-white/5 !text-gray-400 !shadow-none' : ''"
                class="w-full">
                <svg x-show="isUpdating" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span x-text="isUpdating ? 'جاري التحديث...' : 'تحديث مكاني'"></span>
            </x-ios-button>
        </div>
    </div>
</div>
