@props(['label' => '', 'id' => '', 'type' => 'text', 'dir' => 'auto', 'labelWidth' => 'w-16'])

<div class="relative border-b border-black/5 dark:border-white/10 last:border-0 flex items-center px-4">
    @if($label)
        <span class="text-gray-400 dark:text-white/40 text-sm font-medium {{ $labelWidth }}">{{ $label }}</span>
    @endif
    <input {{ $attributes->merge(['type' => $type, 'id' => $id, 'dir' => $dir, 'class' => 'flex-1 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-4 text-sm font-bold placeholder-gray-400 [&:-webkit-autofill]:[transition:background-color_9999999s_ease-in-out_0s] [&:-webkit-autofill]:[-webkit-text-fill-color:inherit] dark:[&:-webkit-autofill]:[-webkit-text-fill-color:#fff]']) }}>
</div>