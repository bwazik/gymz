<div class="min-h-screen p-6 pb-24" x-data x-init="$dispatch('hide-bottom-nav')" @destroyed.window="$dispatch('show-bottom-nav')">
    {{-- Header --}}
    <header class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">المحفظة</h1>
        <button wire:navigate href="{{ route('profile') }}"
            class="text-gray-500 dark:text-gray-400 p-2 -mr-2 bg-black/5 dark:bg-white/5 rounded-full hover:bg-black/10 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </button>
    </header>

    {{-- The Apple Card (Liquid Glass) --}}
    <x-wallet.card :balance="$balance" />

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
