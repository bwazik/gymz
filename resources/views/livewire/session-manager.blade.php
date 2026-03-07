<div>
    {{-- Section Title --}}
    <div class="mb-5 mt-4">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">الجلسات النشطة</h2>
    </div>


    @forelse ($this->activeSessions as $session)
        @php
            $isRoleA = auth()->id() === $session->user_a_id;
            $partner = $isRoleA ? $session->userB : $session->userA;
        @endphp

        <x-glass-card class="p-6 mb-4 backdrop-blur-3xl">

            {{-- Session Header: Partner + Gym --}}
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $partner->image_path ? (Str::startsWith($partner->image_path, 'http') ? $partner->image_path : Storage::url($partner->image_path)) : asset('images/default.jpg') }}"
                    referrerpolicy="no-referrer" alt="{{ $partner->name }}"
                    class="w-11 h-11 rounded-full object-cover border border-black/5 dark:border-white/10">
                <div class="flex-1">
                    <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $partner->name }}</p>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-3.5 h-3.5 text-gymz-accent">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>{{ $session->workoutIntent?->gym?->name ?? 'جيم غير معروف' }}</span>
                    </div>
                </div>

                {{-- Status Badge --}}
                @if ($session->status === \App\Enums\SessionStatus::Scheduled)
                    <span
                        class="px-3 py-1 rounded-full text-[11px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400">
                        مجدولة
                    </span>
                @elseif ($session->status === \App\Enums\SessionStatus::InProgress)
                    <span
                        class="px-3 py-1 rounded-full text-[11px] font-bold bg-gymz-accent/10 text-gymz-accent animate-pulse">
                        جاري التمرين
                    </span>
                @endif
            </div>

            {{-- Scheduled: QR / Scanner --}}
            @if ($session->status === \App\Enums\SessionStatus::Scheduled)
                @if ($isRoleA)
                    {{-- Role A: Show QR Code --}}
                    <div class="bg-black/5 dark:bg-white/5 rounded-2xl p-6 text-center">
                        <div class="inline-block bg-white rounded-2xl p-4 mb-4 shadow-sm">
                            {!! QrCode::size(160)->style('round')->color(15, 23, 42)->generate($session->qr_token) !!}
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                            خلي شريكك يعمل سكان للكود ده عشان تبدأوا 🤝
                        </p>
                    </div>
                @else
                    {{-- Role B: QR Scanner --}}
                    <div x-data="{
                        scanning: false,
                        scanner: null,
                        verified: false,
                        error: '',
                        startScan() {
                            this.scanning = true;
                            this.error = '';
                            this.$nextTick(() => {
                                const readerId = 'reader-{{ $session->id }}';
                                this.scanner = new Html5Qrcode(readerId);
                                this.scanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 220, height: 220 } },
                                    (decodedText) => {
                                        this.scanner.stop().then(() => {
                                            this.scanning = false;
                                            $wire.verifyToken(decodedText, {{ $session->id }});
                                        });
                                    },
                                    () => {}
                                ).catch((err) => {
                                    this.scanning = false;
                                    this.error = 'مقدرناش نفتح الكاميرا. تأكد من الإذن.';
                                });
                            });
                        },
                        stopScan() {
                            if (this.scanner) {
                                this.scanner.stop().catch(() => {});
                            }
                            this.scanning = false;
                        }
                    }" x-on:token-verified.window="verified = true; stopScan()">
                        <div class="bg-black/5 dark:bg-white/5 rounded-2xl p-6 text-center">
                            {{-- Scanner area --}}
                            <div x-show="scanning" class="mb-4">
                                <div id="reader-{{ $session->id }}" class="rounded-2xl overflow-hidden mx-auto"
                                    style="max-width: 300px;"></div>
                                <button @click="stopScan()" type="button"
                                    class="mt-3 text-sm text-gray-500 dark:text-gray-400 underline">
                                    إلغاء
                                </button>
                            </div>

                            {{-- Start scan button --}}
                            <div x-show="!scanning && !verified">
                                <div
                                    class="w-16 h-16 mx-auto mb-4 rounded-full bg-gymz-accent/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gymz-accent">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                                    </svg>
                                </div>
                                <button @click="startScan()" type="button"
                                    class="w-full py-4 rounded-2xl bg-gymz-accent text-white font-bold flex items-center justify-center gap-2 shadow-lg shadow-gymz-accent/20 active:scale-95 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                    </svg>
                                    ابدأ سكان
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                                    وجه الكاميرا على الـ QR Code عند شريكك
                                </p>
                            </div>

                            {{-- Verified state --}}
                            <div x-show="verified" class="py-4">
                                <div
                                    class="w-16 h-16 mx-auto mb-3 rounded-full bg-gymz-accent/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor" class="w-8 h-8 text-gymz-accent">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                                <p class="font-bold text-gymz-accent text-sm">تم التحقق بنجاح! 🔥</p>
                            </div>

                            {{-- Error --}}
                            <p x-show="error" x-text="error" class="text-red-500 text-xs mt-3 font-bold"></p>
                        </div>
                    </div>
                @endif

                {{-- Report No-Show Button (15-min grace period) --}}
                @if ($session->workoutIntent && now() >= $session->workoutIntent->start_time->copy()->addMinutes(15))
                    <div class="mt-4 pt-4 border-t border-black/5 dark:border-white/5">
                        <button type="button"
                            @click="$dispatch('open-ios-alert', { title: 'إلغاء التمرينة للغياب', message: 'متأكد إن الطرف التاني مجاش؟ هيتخصم منه نقط.', action: 'reportNoShow', params: {{ $session->id }}, componentId: $wire.$id })"
                            class="w-full py-2.5 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 font-bold text-sm active:scale-95 transition-all">
                            الإبلاغ عن غياب الطرف التاني
                        </button>
                    </div>
                @endif
            @elseif ($session->status === \App\Enums\SessionStatus::InProgress)
                {{-- In Progress: Anti-Cheat Timer + End Button --}}
                @php
                    $scannedAt = $session->scanned_at;
                    $minutesPassed = $scannedAt ? max(0, (int) $scannedAt->diffInMinutes(now(), false)) : 0;

                    // Calculate remaining (capped between 0 and 90)
                    $remainingMinutes = max(0, 90 - $minutesPassed);
                    $canEnd = $remainingMinutes === 0;
                @endphp

                <div class="bg-black/5 dark:bg-white/5 rounded-2xl p-6 text-center" x-data="{
                    remaining: {{ $remainingMinutes }},
                    canEnd: {{ $canEnd ? 'true' : 'false' }},
                    init() {
                        if (this.remaining > 0) {
                            this.timer = setInterval(() => {
                                this.remaining--;
                                if (this.remaining <= 0) {
                                    this.remaining = 0;
                                    this.canEnd = true;
                                    clearInterval(this.timer);
                                }
                            }, 60000);
                        } else {
                            this.canEnd = true;
                        }
                    }
                }">

                    {{-- Pulse indicator --}}
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gymz-accent/10 text-gymz-accent text-sm font-bold mb-4 animate-pulse">
                        <span class="w-2 h-2 rounded-full bg-gymz-accent"></span>
                        جاري التمرين
                    </div>

                    {{-- Anti-cheat message --}}
                    <template x-if="!canEnd">
                        <div class="mb-4">
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium mb-1">
                                عاش يا وحوش! استمروا 🏋️
                            </p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                متبقي <span x-text="remaining" class="text-gymz-accent"></span> دقيقة لتفعيل زر النقط
                            </p>
                        </div>
                    </template>

                    <template x-if="canEnd">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                            خلصتوا تمرينة؟ اضغط عشان تاخد ١٠ جلوتس 💪
                        </p>
                    </template>

                    <button wire:loading.attr="disabled"
                        @click="if (canEnd) { $dispatch('open-ios-alert', { title: 'إنهاء التمرينة', message: 'متأكد إنك عايز تنهي التمرينة وتستلم الـ ١٠ جلوتس', action: 'endSession', params: {{ $session->id }}, componentId: $wire.$id }) }"
                        class="w-full py-3.5 rounded-2xl font-bold text-sm transition-all active:scale-95 disabled:opacity-50"
                        :class="canEnd
                            ?
                            'bg-gymz-accent text-white shadow-md' :
                            'bg-gray-200 dark:bg-white/10 text-gray-400 dark:text-white/30 cursor-not-allowed'"
                        :disabled="!canEnd">
                        <span wire:loading.remove wire:target="endSession({{ $session->id }})">إنهاء التمرينة واستلام
                            ١٠ جلوتس 🍑</span>
                        <span wire:loading wire:target="endSession({{ $session->id }})">اتقل...</span>
                    </button>
                </div>
            @endif
        </x-glass-card>
    @empty
        {{-- Empty State --}}
        <x-glass-card class="p-8 text-center">
            <div
                class="w-16 h-16 mx-auto mb-4 rounded-full bg-black/5 dark:bg-white/5 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-700 dark:text-white/70 mb-1">مفيش جلسات حالياً</h3>
            <p class="text-sm text-gray-500 dark:text-gray-500">ابدأ تمرينة جديدة من الرئيسية!</p>
        </x-glass-card>
    @endforelse
</div>

{{-- html5-qrcode CDN --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
