@props(['target' => null])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full mt-4 py-3.5 rounded-2xl bg-gymz-accent text-white font-bold active:scale-95 transition-all shadow-lg shadow-gymz-accent/20 disabled:opacity-50 flex justify-center items-center gap-2']) }}>
    @if($target)
        <span wire:loading.remove wire:target="{{ $target }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $target }}" class="w-5 h-5 border-2 border-white border-t-transparent flex rounded-full animate-spin"></span>
        <span wire:loading wire:target="{{ $target }}">جاري الحفظ...</span>
    @else
        {{ $slot }}
    @endif
</button>