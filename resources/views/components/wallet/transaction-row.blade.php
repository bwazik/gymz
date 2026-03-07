@props(['transaction', 'isLast' => false])

@php
    use App\Enums\TransactionType;

    $isEarned = $transaction->type === TransactionType::Earned;
    $amountClass = $isEarned ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400';
    $amountPrefix = $isEarned ? '+' : '-';
    $iconBg = $isEarned ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500';
    $amountDisplay = abs($transaction->amount);
@endphp

<div
    class="flex items-center justify-between p-4 {{ !$isLast ? 'border-b border-black/5 dark:border-white/10' : '' }} hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-full {{ $iconBg }} flex items-center justify-center shrink-0">
            @if ($isEarned)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                </svg>
            @endif
        </div>

        <div>
            <p class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">{{ $transaction->description }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $transaction->created_at->diffForHumans() }}
            </p>
        </div>
    </div>

    <div class="text-left shrink-0">
        <p class="text-base font-black {{ $amountClass }} flex items-center justify-end gap-1">
            <span dir="ltr">{{ $amountPrefix }}{{ $amountDisplay }}</span>
        </p>
    </div>
</div>
