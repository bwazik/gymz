<div class="relative z-[60] flex-1 max-w-[150px] sm:max-w-[200px]">
    <x-ios-select wire:model.live="selectedCityId" :options="$cities->pluck('name', 'id')" placeholder="المدينة...">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-gymz-accent">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
        </x-slot:icon>
    </x-ios-select>
</div>
