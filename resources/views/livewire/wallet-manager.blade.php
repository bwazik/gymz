<div class="min-h-screen p-6 pb-24" x-data x-init="$dispatch('hide-bottom-nav')" @destroyed.window="$dispatch('show-bottom-nav')">
    {{-- Header --}}
    <header class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">الجلوتس</h1>
        <button wire:navigate href="{{ route('profile') }}" @click="$dispatch('show-bottom-nav')"
            class="text-gray-500 dark:text-gray-400 p-2 -mr-2 bg-black/5 dark:bg-white/5 rounded-full hover:bg-black/10 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </button>
    </header>

    {{-- The Apple Card (Liquid Glass) --}}
    <x-wallet.card :balance="$balance" />

    {{-- Invite a Gym Bro Card --}}
    <div
        class="mt-6 bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-3xl p-5 shadow-sm relative overflow-hidden">
        {{-- Ambient Glow --}}
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-gymz-accent/20 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-2xl bg-gymz-accent/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5 text-gymz-accent">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">اعزم جيماوي 🔗</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">أي حد هيسجل من اللينك بتاعك، هتكسبوا إنتو
                        الاتنين <span class="text-gymz-accent font-bold">10 جلوتس</span> بعد أول تمرينة ليه 🏋🏽</p>
                </div>
            </div>

            <div x-data="{ copied: false }"
                class="mt-4 flex items-center justify-between gap-4 bg-black/5 dark:bg-white/5 p-3 rounded-2xl border border-black/5 dark:border-white/5">
                <span class="text-sm font-mono text-gray-700 dark:text-gray-300 truncate"
                    dir="ltr">{{ url('/login?ref=' . Auth::user()->referral_code) }}</span>
                <button
                    @click="
                        navigator.clipboard.writeText('{{ url('/login?ref=' . Auth::user()->referral_code) }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                        $dispatch('toast-success', 'تم نسخ الرابط بنجاح! 📋');
                    "
                    class="shrink-0 px-4 py-2 bg-gymz-accent text-white text-xs font-bold rounded-xl active:scale-95 transition-all flex items-center justify-center min-w-[70px]">
                    <span x-show="!copied">نسخ</span>
                    <span x-show="copied" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Transactions Ledger --}}
    <div class="mt-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 px-1">سجل العمليات</h2>

        <div
            class="bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-3xl overflow-hidden shadow-sm">
            @forelse ($transactions as $transaction)
                <x-wallet.transaction-row :transaction="$transaction" :is-last="$loop->last" />
            @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                    لسه مفيش أي عمليات في المحفظة بتاعتك. انزل اتمرن وجمع جلوتس!
                </div>
            @endforelse
        </div>
    </div>

    {{-- Rewards Store Teaser --}}
    <div class="mt-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 px-1 flex items-center justify-between">
            <span>المتجر</span>
            <span
                class="text-[10px] font-black tracking-widest bg-gymz-accent/20 text-gymz-accent px-2.5 py-1 rounded-full uppercase">قريباً</span>
        </h2>

        <div class="space-y-3">
            <x-wallet.reward-teaser title="مشروب طاقة (Red Bull)" description="عشان تفوق قبل التمرينة الجاية" />
            <x-wallet.reward-teaser title="كيرياتين 30 سكوب" description="عشان تزود قوتك وتحسن أداءك في التمرين" />
            <x-wallet.reward-teaser title="اشتراك جيم (شهر)" description="اتمرن براحتك في أحسن الجيمات" />
            <x-wallet.reward-teaser title="تيشيرت كومبريشن" description="(مبيتراحش بيه الدروس)" />
            <x-wallet.reward-teaser title="بروتين بارز" description="سناك بعد التمرين" />
        </div>
    </div>
</div>
