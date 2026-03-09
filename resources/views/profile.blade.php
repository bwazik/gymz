<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            الملف الشخصي
        </h2>
    </x-slot>

    {{-- Settings Sections --}}
    <div class="mt-6 space-y-6">
        <livewire:profile-manager />

        {{-- Push Notifications Toggle --}}
        <div class="px-4" x-data="{
            isEnabled: Notification.permission === 'granted',
            isSupported: 'Notification' in window,
            async toggle() {
                if (Notification.permission === 'granted') {
                    this.isEnabled = true;
                    alert('لإيقاف الإشعارات، يجب تغيير الإعدادات من إعدادات المتصفح أو النظام مباشرة.');
                    return;
                }
        
                const success = await window.requestFCMToken();
                this.isEnabled = success;
            }
        }">
            <header class="mb-4 mt-8 text-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    تنبيهات النظام
                </h2>
            </header>

            <x-ios-input-group class="p-4">
                <template x-if="isSupported">
                    <x-ios-toggle label="إشعارات التطبيق" description="استلام كل إشعارات التطبيق الهامة والتمارين"
                        x-model="isEnabled" @change="toggle()" />
                </template>
                <template x-if="!isSupported">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">إشعارات التطبيق</p>
                            <p class="text-xs text-red-500 font-medium italic">غير مدعوم على هذا المتصفح</p>
                        </div>
                    </div>
                </template>
            </x-ios-input-group>
        </div>

        <livewire:profile.update-password />
        <livewire:profile.delete-account />

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
