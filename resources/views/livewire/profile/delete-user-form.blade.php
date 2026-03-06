<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-4">
    <header class="mb-4 text-center">
        <h2 class="text-lg font-bold text-red-500">
            منطقة الخطر
        </h2>
        <p class="mt-1 text-xs text-gray-500 dark:text-white/50 px-4 leading-relaxed">
            بمجرد حذف حسابك، سيتم مسح جميع بياناتك بشكل نهائي ولا يمكن التراجع عن هذا الإجراء.
        </p>
    </header>

    <div
        class="bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-red-500/20 rounded-3xl p-5 shadow-sm text-center">
        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="w-full py-3.5 rounded-2xl bg-red-500/10 text-red-500 dark:text-red-400 font-bold active:scale-95 transition-all">
            حذف الحساب نهائياً
        </button>
    </div>

    {{-- Apple Style Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6 text-center">

            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-red-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h2 class="text-[17px] font-bold text-gray-900 dark:text-white mb-2">
                مـتأكد إنك هتحذف الحساب؟
            </h2>

            <p class="text-[13px] text-gray-500 dark:text-gray-400 leading-relaxed mb-6">
                يرجى إدخال كلمة السر الخاصة بك لتأكيد رغبتك في حذف الحساب نهائياً.
            </p>

            <div
                class="mb-6 bg-black/5 dark:bg-white/5 rounded-2xl px-4 py-1 border border-black/5 dark:border-white/10">
                <input wire:model="password" id="password" type="password"
                    class="w-full bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-3 text-sm font-bold placeholder-gray-400 text-center"
                    dir="ltr" required placeholder="كلمة السر">
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2 mb-4" />

            <div class="border-t border-black/5 dark:border-white/10 -mx-6 -mb-6 flex mt-4">
                <button type="button" x-on:click="$dispatch('close')"
                    class="flex-1 py-4 text-[17px] font-bold text-gray-500 dark:text-white/50 border-r border-black/5 dark:border-white/10 active:bg-black/5 dark:active:bg-white/10 transition-colors">
                    إلغاء
                </button>

                <button type="submit" wire:loading.attr="disabled"
                    class="flex-1 py-4 text-[17px] font-bold text-red-500 active:bg-black/5 dark:active:bg-white/10 transition-colors">
                    <span wire:loading.remove wire:target="deleteUser">حذف نهائي</span>
                    <span wire:loading wire:target="deleteUser">جاري الحذف...</span>
                </button>
            </div>
        </form>
    </x-modal>
</section>
