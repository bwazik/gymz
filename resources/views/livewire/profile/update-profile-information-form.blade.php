<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->phone = Auth::user()->phone ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if ($this->photo) {
            $path = $this->photo->store('avatars', 'public');
            $user->image_path = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('toast', message: 'تم تحديث البيانات بنجاح! ✅', type: 'success');
        $this->photo = null; // Clear photo to prevent re-upload on next submit
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
    <header class="mb-4 text-center">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
            المعلومات الأساسية
        </h2>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-4">
        {{-- Profile Picture Upload --}}
        <div class="flex justify-center mb-6">
            <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-dashed border-gray-300 dark:border-white/20 cursor-pointer group"
                onclick="document.getElementById('photo-upload').click()">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                @else
                    <img src="{{ auth()->user()->image_path ? Storage::url(auth()->user()->image_path) : asset('images/default.jpg') }}"
                        class="w-full h-full object-cover group-hover:opacity-50 transition-opacity">
                @endif

                <div
                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6 text-white">
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
            <input type="file" id="photo-upload" wire:model="photo" class="hidden" accept="image/*">
        </div>

        <x-input-error class="text-center mt-2" :messages="$errors->get('photo')" />

        {{-- Glass Container for Fields --}}
        <div
            class="bg-white/70 dark:bg-[#1c1c1e]/70 backdrop-blur-3xl border border-black/5 dark:border-white/10 rounded-3xl overflow-hidden shadow-sm">

            {{-- Name --}}
            <div class="relative border-b border-black/5 dark:border-white/10 flex items-center px-4">
                <span class="text-gray-400 dark:text-white/40 w-16 text-sm font-medium">الاسم</span>
                <input wire:model="name" id="name" type="text"
                    class="flex-1 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-4 text-sm font-bold placeholder-gray-400"
                    required autofocus placeholder="اسمك">
            </div>

            {{-- Email --}}
            <div class="relative border-b border-black/5 dark:border-white/10 flex items-center px-4">
                <span class="text-gray-400 dark:text-white/40 w-16 text-sm font-medium">الإيميل</span>
                <input wire:model="email" id="email" type="email"
                    class="flex-1 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-4 text-sm font-bold placeholder-gray-400 text-left"
                    dir="ltr" required placeholder="email@example.com">
            </div>

            {{-- Phone --}}
            <div class="relative flex items-center px-4">
                <span class="text-gray-400 dark:text-white/40 w-16 text-sm font-medium">الموبايل</span>
                <input wire:model="phone" id="phone" type="tel"
                    class="flex-1 bg-transparent border-0 focus:ring-0 text-gray-900 dark:text-white px-2 py-4 text-sm font-bold placeholder-gray-400 text-left"
                    dir="ltr" placeholder="01xxxxxxxxx">
            </div>

        </div>

        <x-input-error class="mt-2" :messages="$errors->get('name')" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />
        <x-input-error class="mt-2" :messages="$errors->get('phone')" />

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

        <button type="submit" wire:loading.attr="disabled"
            class="w-full mt-4 py-3.5 rounded-2xl bg-gymz-accent text-white font-bold active:scale-95 transition-all shadow-lg shadow-gymz-accent/20 disabled:opacity-50 flex justify-center items-center gap-2">
            <span wire:loading.remove wire:target="updateProfileInformation">حفظ البيانات</span>
            <span wire:loading wire:target="updateProfileInformation"
                class="w-5 h-5 border-2 border-white border-t-transparent flex rounded-full animate-spin"></span>
            <span wire:loading wire:target="updateProfileInformation">جاري الحفظ...</span>
        </button>
    </form>
</section>
