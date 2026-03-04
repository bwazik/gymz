<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>

    <livewire:workout-feed />
</x-app-layout>
