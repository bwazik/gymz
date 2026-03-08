<div x-data="{ open: false }" class="relative">
    <!-- The Trigger -->
    <button wire:click="loadNotifications" x-on:click="open = !open" @click.away="open = false"
        class="p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-all duration-200 active:scale-90 relative">
        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <!-- Red Badge for Unread Notifications -->
        @if ($unreadCount > 0)
            <span
                class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-[#1c1c1e]"></span>
        @endif
    </button>

    <!-- The Panel/Dropdown -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute left-0 mt-2 w-80 bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-3xl shadow-lg z-50 overflow-hidden"
        style="display: none;" dir="rtl">

        <div class="p-4 border-b border-black/5 dark:border-white/10 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">الإشعارات</h3>
            @if ($unreadCount > 0)
                <button wire:click.stop="markAllAsRead"
                    class="text-xs text-gymz-accent font-medium hover:underline focus:outline-none">
                    تحديد الكل كمقروء
                </button>
            @endif
        </div>

        <!-- Loading State -->
        <div wire:loading wire:target="loadNotifications, markAllAsRead" class="p-6 text-center w-full">
            <svg class="animate-spin h-6 w-6 text-gymz-accent mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium">جاري التحميل...</p>
        </div>

        <!-- The List -->
        <div wire:loading.remove wire:target="loadNotifications, markAllAsRead" class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification['read_at']);
                    $data = $notification['data'];
                @endphp
                <div wire:click="markAsRead('{{ $notification['id'] }}')"
                    class="flex items-start gap-3 p-4 border-b border-black/5 dark:border-white/10 cursor-pointer transition-colors {{ $isUnread ? 'bg-black/5 dark:bg-white/5 hover:bg-black/10 dark:hover:bg-white/10' : 'hover:bg-black/5 dark:hover:bg-white/5' }}">

                    @if (isset($data['icon']) && !empty($data['icon']))
                        @php
                            // Ensure the icon resolves to a valid Blade Heroicon component name
                            $iconString = $data['icon'];
                            if (str_starts_with($iconString, 'heroicon-')) {
                                $iconTag = $iconString;
                            } else {
                                $iconTag = 'heroicon-o-' . $iconString;
                            }
                        @endphp
                        <div
                            class="shrink-0 rounded-full p-2 {{ isset($data['icon_bg']) && !empty($data['icon_bg']) ? 'bg-' . $data['icon_bg'] : 'bg-gymz-accent' }} {{ isset($data['icon_color']) && !empty($data['icon_color']) ? 'text-' . $data['icon_color'] : 'text-white' }}">
                            <x-dynamic-component :component="$iconTag" class="w-5 h-5" />
                        </div>
                    @else
                        <div class="shrink-0 rounded-full p-2 bg-gymz-accent/10 text-gymz-accent">
                            <!-- Fallback Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                            {{ $data['title'] ?? 'إشعار جديد' }}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5"
                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 font-medium">
                            {{ \Carbon\Carbon::parse($notification['created_at'])->locale('ar')->diffForHumans() }}
                        </p>
                    </div>

                    @if ($isUnread)
                        <div class="shrink-0 flex items-center justify-center mt-2.5">
                            <span class="block h-2 w-2 rounded-full bg-gymz-accent shadow-sm"></span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center flex flex-col items-center justify-center">
                    <p class="text-3xl mb-3">📭</p>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">لا توجد إشعارات حالياً</p>
                </div>
            @endforelse

            @if ($hasMore)
                <div class="p-3 border-t border-black/5 dark:border-white/10 text-center bg-gray-50 dark:bg-black/10">
                    <button wire:click.stop="loadMore"
                        class="text-sm text-gymz-accent font-medium hover:underline focus:outline-none">
                        <span wire:loading.remove wire:target="loadMore">عرض المزيد</span>
                        <span wire:loading wire:target="loadMore">جاري التحميل...</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
