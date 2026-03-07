<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            الملف الشخصي
        </h2>
    </x-slot>

    {{-- Settings Sections --}}
    <div class="mt-6 space-y-6">
        <livewire:profile-manager />
        <livewire:profile.update-password-form />
        <livewire:profile.delete-user-form />

        {{-- Logout Button --}}
        <div class="px-4 pb-12">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full py-4 text-center font-bold text-red-500 bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-2xl active:scale-95 transition-all">
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
