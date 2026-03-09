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
                if (this.isEnabled) {
                    alert('لإيقاف الإشعارات، يجب تغيير الإعدادات من إعدادات المتصفح أو النظام مباشرة.');
                    return;
                }
        
                const success = await window.requestFCMToken();
                if (success) {
                    this.isEnabled = true;
                }
            }
        }">
            <div
                class="p-5 bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-3xl flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-2xl bg-gymz-accent/10 flex items-center justify-center text-gymz-accent text-xl">
                        <i class="heroicon-o-bell"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">إشعارات التمارين</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">كن أول من يعرف بطلبات التمرين الجديدة</p>
                    </div>
                </div>

                <template x-if="isSupported">
                    <button @click="toggle()" :class="isEnabled ? 'bg-gymz-accent' : 'bg-gray-200 dark:bg-gray-700'"
                        class="relative inline-flex h-7 w-12 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-0">
                        <span aria-hidden="true" :class="isEnabled ? 'translate-x-5' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </template>
                <template x-if="!isSupported">
                    <span class="text-xs text-red-500 font-medium italic">غير مدعوم</span>
                </template>
            </div>
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
