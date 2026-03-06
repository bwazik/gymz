<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            الملف الشخصي
        </h2>
    </x-slot>

    {{-- Settings Sections --}}
    <div class="mt-6 space-y-6">
        <livewire:profile.update-profile-information-form />
        <livewire:profile.update-password-form />
        <livewire:profile.delete-user-form />
    </div>
</x-app-layout>
