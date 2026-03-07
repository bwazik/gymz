<?php

use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\WorkoutSession;
use App\Enums\SessionStatus;
use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Enums\RequestStatus;
use Livewire\Attributes\Computed;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $photo;
    public bool $showHistoryModal = false;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->phone = Auth::user()->phone ?? '';
    }

    public function messages(): array
    {
        return [
            'name.required' => 'لازم تكتب اسمك',
            'name.max' => 'الاسم طويل جداً',
            'email.required' => 'الإيميل مطلوب',
            'email.email' => 'صيغة الإيميل مش صحيحة',
            'email.unique' => 'الإيميل ده متسجل قبل كده',
            'phone.unique' => 'رقم الموبايل متسجل قبل كده',
            'photo.image' => 'الملف لازم يكون صورة',
            'photo.mimes' => 'الصورة لازم تكون بصيغة jpeg, png, أو webp',
            'photo.max' => 'حجم الصورة لازم ما يتخطاش 3 ميجابايت',
        ];
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $key = 'update-profile:' . Auth::id();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "محاولات كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }

        RateLimiter::hit($key, 60);

        $user = Auth::user();

        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'phone' => ['nullable', 'string', 'max:255', new PhoneNumber(), Rule::unique(User::class)->ignore($user->id)],
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:3072'],
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            throw $e;
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'] ?: null,
            'phone' => $validated['phone'] ?: null,
        ]);

        if ($this->photo) {
            $path = $this->photo->store('avatars', 'public');
            $user->image_path = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('toast', message: 'تم تحديث البيانات بنجاح!', type: 'success');
        $this->photo = null; // Clear photo to prevent re-upload on next submit
    }

    /**
     * Delete an active workout intent.
     */
    public function deleteIntent(int $intentId): void
    {
        $intent = Auth::user()->workoutIntents()->find($intentId);

        if (!$intent) {
            return;
        }

        $session = WorkoutSession::where('intent_id', $intent->id)
            ->whereIn('status', [SessionStatus::Scheduled, SessionStatus::InProgress])
            ->first();

        if ($session) {
            $session->update(['status' => SessionStatus::Cancelled_By_Host]);

            $user = Auth::user();
            $user->reliability_score = max(0, $user->reliability_score - 5);
            $user->save();

            $this->dispatch('toast', message: 'تم إلغاء التمرينة وخصم 5% من الموثوقية لإلغاء اتفاق مؤكد.', type: 'error');
        } else {
            $this->dispatch('toast', message: 'تم إلغاء التمرينة بنجاح.', type: 'success');
        }

        $intent->workoutRequests()->delete();
        $intent->delete();
    }

    #[Computed]
    public function historyItems(): Collection
    {
        $userId = Auth::id();

        // 1. Intents where user is host, time passed, NO session exists
        $intentsWithoutSessions = Auth::user()
            ->workoutIntents()
            ->where('start_time', '<', now())
            ->doesntHave('workoutSession')
            ->with(['gym', 'workoutTarget'])
            ->get()
            ->map(function ($intent) {
                return (object) [
                    'target_name' => $intent->workoutTarget?->name ?? 'تمرينة عامة',
                    'gym_name' => $intent->gym?->name ?? 'أي جيم',
                    'start_time' => $intent->start_time,
                    'badge_status' => 'no_show_host',
                ];
            });

        // 2. Sessions where user is involved, safely loading soft-deleted intents
        $sessions = \App\Models\WorkoutSession::with(['workoutIntent' => fn($q) => $q->withTrashed()->with(['gym', 'workoutTarget'])])
            ->where(function ($q) use ($userId) {
                $q->where('user_a_id', $userId)->orWhere('user_b_id', $userId);
            })
            ->whereHas('workoutIntent', function ($q) {
                $q->withTrashed()->where('start_time', '<', now());
            })
            ->get()
            ->map(function ($session) {
                $intent = $session->workoutIntent;
                return (object) [
                    'target_name' => $intent->workoutTarget?->name ?? 'تمرينة عامة',
                    'gym_name' => $intent->gym?->name ?? 'أي جيم',
                    'start_time' => $intent->start_time,
                    'badge_status' => $session->status,
                ];
            });

        return $intentsWithoutSessions->concat($sessions)->sortByDesc('start_time')->take(15);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    {{-- Fitness CV Card --}}
    <div
        class="bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-[2rem] p-6 mb-6">
        {{-- Header: Avatar + Info --}}
        <div class="flex items-center gap-4 mb-5">
            {{-- Clickable Avatar for Photo Upload --}}
            <div class="relative w-20 h-20 rounded-full overflow-hidden border border-black/5 dark:border-white/10 cursor-pointer group"
                onclick="document.getElementById('photo-upload').click()">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ auth()->user()->image_path ? (Str::startsWith(auth()->user()->image_path, 'http') ? auth()->user()->image_path : Storage::url(auth()->user()->image_path)) : asset('images/default.jpg') }}"
                        referrerpolicy="no-referrer"
                        class="w-full h-full object-cover group-hover:opacity-50 transition-opacity">
                @endif

                <div
                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                </div>

                <div wire:loading wire:target="photo"
                    class="absolute inset-0 bg-black/60 flex items-center justify-center">
                    <span class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                </div>
            </div>
            <input type="file" id="photo-upload" wire:model="photo" class="hidden"
                accept="image/jpeg,image/png,image/webp">

            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                @if (auth()->user()->phone)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->phone }}</p>
                @endif
            </div>
        </div>



        {{-- Badges Row --}}
        <div class="flex flex-wrap items-center gap-2 mb-5">
            {{-- Level Badge --}}
            @php
                $levelClasses = match (auth()->user()->level) {
                    \App\Enums\UserLevel::Beginner
                        => 'bg-blue-500/15 text-blue-600 dark:text-blue-400 border-blue-500/30',
                    \App\Enums\UserLevel::Mid => 'bg-gymz-accent/15 text-gymz-accent border-gymz-accent/30',
                    \App\Enums\UserLevel::Pro
                        => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                    default
                        => 'bg-gray-100 text-gray-500 border-gray-200 dark:bg-white/10 dark:text-white/50 dark:border-white/10',
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $levelClasses }}">
                {{ auth()->user()->level?->getLabel() ?? 'مبتدئ' }}
            </span>

            {{-- Gender Badge --}}
            @if (auth()->user()->gender)
                <span
                    class="px-3 py-1 rounded-full text-[11px] font-bold border bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/70 border-black/5 dark:border-white/10">
                    {{ auth()->user()->gender->getLabel() }}
                </span>
            @endif

            {{-- DOB Badge --}}
            @if (auth()->user()->dob)
                <span
                    class="px-3 py-1 rounded-full text-[11px] font-bold border bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/70 border-black/5 dark:border-white/10">
                    {{ \Carbon\Carbon::parse(auth()->user()->dob)->translatedFormat('j F Y') }}
                </span>
            @endif
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Glutes Balance --}}
            <div class="bg-black/5 dark:bg-white/5 rounded-2xl p-4 text-center">
                <div class="flex items-center justify-center gap-1.5 mb-2">
                    <img src="{{ asset('images/peach.svg') }}" class="w-4 h-4 scale-125" alt="glutes">
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">الجلوتس</span>
                </div>
                <p class="text-xl font-black text-gray-900 dark:text-white">
                    {{ number_format(auth()->user()->glutes_balance) }}</p>
            </div>

            {{-- Reliability Score --}}
            @php
                $score = auth()->user()->reliability_score;
                $scoreColor = $score > 80 ? 'text-green-500' : ($score > 50 ? 'text-amber-500' : 'text-red-500');
            @endphp
            <div class="bg-black/5 dark:bg-white/5 rounded-2xl p-4 text-center">
                <div class="flex items-center justify-center gap-1.5 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4 {{ $scoreColor }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">الموثوقية</span>
                </div>
                <p class="text-xl font-black {{ $scoreColor }}">{{ $score }}%</p>
            </div>
        </div>
    </div>

    {{-- My Active Workouts Section --}}
    <div class="flex items-center justify-between mb-4 mt-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">تمريناتي الحالية</h3>
        <button type="button" wire:click="$set('showHistoryModal', true)"
            class="text-xs font-bold text-gymz-accent bg-gymz-accent/10 px-3 py-1.5 rounded-full active:scale-95 transition-all">
            السجل
        </button>
    </div>

    @forelse (auth()->user()->workoutIntents()->where('start_time', '>=', now())->orderBy('start_time')->get() as $myIntent)
        <div
            class="bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-2xl p-4 mb-3 flex items-center justify-between">
            <div class="flex flex-col">
                <span
                    class="font-bold text-sm text-gray-900 dark:text-white">{{ $myIntent->workoutTarget?->name ?? 'تمرينة عامة' }}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $myIntent->gym?->name ?? 'أي جيم' }} • {{ $myIntent->start_time->format('g:i A') }}
                </span>
            </div>

            <button type="button"
                @click="$dispatch('open-ios-alert', { title: 'إلغاء التمرينة', message: 'متأكد إنك عايز تلغي التمرينة دي؟', action: 'deleteIntent', params: {{ $myIntent->id }}, componentId: $wire.$id })"
                class="text-red-500 bg-red-50 dark:bg-red-500/10 rounded-full p-2 active:scale-95 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
        </div>
    @empty
        <div
            class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-black/5 dark:border-white/10 rounded-2xl p-6 text-center mb-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">معندكش تمارين جاية.. انزل الجيم يابطل! 💪
            </p>
        </div>
    @endforelse



    <header class="mb-4 mt-8 text-center">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
            المعلومات الأساسية
        </h2>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-4">
        {{-- Glass Container for Fields --}}
        <x-ios-input-group>

            {{-- Name --}}
            <x-ios-input label="الاسم" id="name" wire:model="name" placeholder="اسمك" required autofocus
                maxlength="255" />

            {{-- Email --}}
            <x-ios-input label="الإيميل" id="email" type="email" wire:model="email" dir="ltr"
                placeholder="email@example.com" required maxlength="255" />

            {{-- Phone --}}
            <x-ios-input label="الموبايل" id="phone" type="tel" wire:model="phone" dir="ltr"
                placeholder="01xxxxxxxxx" maxlength="11" pattern="^(011|010|012|015)\d{8}$" />

        </x-ios-input-group>



        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
            <div class="px-4">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    لم يتم التحقق من الإيميل.
                    <button wire:click.prevent="sendVerification" class="underline text-sm text-gymz-accent">
                        اضغط هنا لإعادة إرسال رابط التحقق.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                        تم إرسال رابط جديد.
                    </p>
                @endif
            </div>
        @endif

        <x-ios-button target="updateProfileInformation" wire:loading.attr="disabled">حفظ البيانات</x-ios-button>
    </form>
    {{-- History Modal Overlay --}}
    <div x-data="{ open: @entangle('showHistoryModal').live }" x-show="open" x-init="$watch('open', val => $dispatch(val ? 'hide-bottom-nav' : 'show-bottom-nav'))" style="display: none;"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black/40 backdrop-blur-md flex items-end justify-center"
        @click.self="open = false">
        {{-- Bottom Sheet Modal --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-out duration-300" x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="bg-white/80 dark:bg-[#1c1c1e]/80 backdrop-blur-3xl border-t border-white/50 dark:border-white/10 p-6 rounded-t-[2rem] w-full max-w-md shadow-2xl pb-[calc(1.5rem+env(safe-area-inset-bottom))] max-h-[85vh] flex flex-col"
            @click.stop>
            {{-- Drag Handle --}}
            <div class="flex justify-center mb-4 shrink-0">
                <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-white/20"></div>
            </div>

            {{-- Title & Close Button --}}
            <div class="flex items-center justify-between mb-5 shrink-0">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">سجل التمارين</h3>
                <button @click="open = false" type="button"
                    class="p-2 bg-gray-100 dark:bg-white/10 text-gray-500 dark:text-white/70 rounded-full active:scale-95 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- History List --}}
            <div class="overflow-y-auto space-y-3 pb-4">
                @forelse ($this->historyItems as $item)
                    @php
                        $badgeText = 'غير معروف';
                        $badgeColors = 'bg-gray-200 dark:bg-white/10 text-gray-600 dark:text-gray-300';
                        $icon =
                            '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';

                        if ($item->badge_status === 'no_show_host') {
                            $badgeText = 'محدش انضم';
                        } elseif ($item->badge_status === \App\Enums\SessionStatus::Completed) {
                            $badgeText = 'تمت';
                            $badgeColors = 'bg-green-500/10 text-green-500';
                            $icon =
                                '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>';
                        } elseif ($item->badge_status === \App\Enums\SessionStatus::Missed) {
                            $badgeText = 'فائتة ❌';
                            $badgeColors = 'bg-red-500/10 text-red-500';
                        } elseif ($item->badge_status === \App\Enums\SessionStatus::Cancelled_By_Host) {
                            $badgeText = 'إلغاء الكابتن';
                            $badgeColors = 'bg-red-500/10 text-red-500';
                        } elseif ($item->badge_status === \App\Enums\SessionStatus::Cancelled_By_Guest) {
                            $badgeText = 'إلغاء الضيف';
                        }
                    @endphp
                    <x-glass-card class="flex items-center justify-between opacity-80 !mb-0 !p-4">
                        <div class="flex flex-col">
                            <span
                                class="font-bold text-sm text-gray-900 dark:text-white">{{ $item->target_name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $item->gym_name }} •
                                {{ \Carbon\Carbon::parse($item->start_time)->translatedFormat('j F Y - g:i A') }}
                            </span>
                        </div>
                        <span
                            class="text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1 shrink-0 {{ $badgeColors }}">
                            {!! $icon !!} {{ $badgeText }}
                        </span>
                    </x-glass-card>
                @empty
                    <div class="text-center py-6 text-sm text-gray-500 dark:text-gray-400">لسه معندكش تاريخ رياضي..
                        ابدأ أول تمرينة ليك! 🏋️‍♂️</div>
                @endforelse
            </div>
        </div>
    </div>
</section>
