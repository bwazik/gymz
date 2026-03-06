<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        try {
            $this->validate([
                'password' => ['required', 'string', 'current_password'],
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            throw $e;
        }

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

    {{-- Pure iOS Prompt Alert --}}
    <div x-data="{ show: @entangle($errors->isNotEmpty()) }" @open-modal.window="if ($event.detail === 'confirm-user-deletion') show = true"
        @close.window="show = false" x-show="show" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[110] flex items-center justify-center bg-black/40 backdrop-blur-sm"
        style="display: none;">

        <form wire:submit="deleteUser" x-show="show" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="w-[270px] bg-white/80 dark:bg-[#2c2c2e]/80 backdrop-blur-3xl rounded-3xl flex flex-col text-center overflow-hidden border border-black/5 dark:border-white/10 shadow-2xl"
            @click.stop>

            <div class="px-6 pt-6 pb-4">
                <h3 class="text-[17px] font-bold text-gray-900 dark:text-white mb-1">متأكد إنك هتحذف الحساب؟</h3>
                <p class="text-[13px] text-gray-500 dark:text-gray-400 leading-relaxed mb-4">يرجى إدخال كلمة السر
                    للتأكيد.</p>

                <div class="bg-black/5 dark:bg-white/5 rounded-xl px-2 py-1 border border-black/5 dark:border-white/10">
                    <input wire:model="password" type="password"
                        class="w-full bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-2 text-[13px] font-bold placeholder-gray-400 text-center [&:-webkit-autofill]:[transition:background-color_9999999s_ease-in-out_0s] [&:-webkit-autofill]:[-webkit-text-fill-color:inherit] dark:[&:-webkit-autofill]:[-webkit-text-fill-color:#fff]"
                        dir="ltr" required placeholder="كلمة السر">
                </div>


            </div>

            <div class="border-t border-black/5 dark:border-white/10 flex">
                <button type="button" @click="show = false"
                    class="flex-1 py-3 text-[17px] font-bold text-gymz-accent border-l border-black/5 dark:border-white/10 active:bg-black/5 dark:active:bg-white/10 transition-colors">
                    إلغاء
                </button>
                <button type="submit"
                    class="flex-1 py-3 text-[17px] font-bold text-red-500 active:bg-black/5 dark:active:bg-white/10 transition-colors">
                    حذف نهائي
                </button>
            </div>
        </form>
    </div>
</section>
