<?php

namespace App\Livewire;

use App\Enums\Gender;
use App\Enums\UserLevel;
use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Onboarding extends Component
{
    public string $phone = '';
    public string $gender = '';
    public $dob = '';
    public string $level = '';

    public function messages(): array
    {
        return [
            'phone.unique' => 'رقم الموبايل متسجل قبل كده',
            'phone.required' => 'لازم تكتب رقم موبايلك',
            'gender.required' => 'حدد النوع عشان نقدر نوجهك صح',
            'dob.required' => 'تاريخ ميلادك مهم للتوثيق',
            'dob.before' => 'مش معقول تكون اتولدت في المستقبل! 😄',
            'level.required' => 'حدد مستواك عشان نضبط لك التمرين',
        ];
    }

    public function mount()
    {
        if (Auth::user()->is_onboarded) {
            return redirect()->route('home');
        }
    }

    public function save()
    {
        $key = 'onboarding:' . Auth::id();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "محاولات كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }

        RateLimiter::hit($key, 60);

        try {
            $this->validate([
                'phone' => ['required', 'string', 'max:255', new PhoneNumber(), Rule::unique(User::class)->ignore(Auth::id())],
                'gender' => ['required', new Enum(Gender::class)],
                'dob' => ['required', 'date', 'before:today'],
                'level' => ['required', new Enum(UserLevel::class)],
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            throw $e;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'phone' => $this->phone,
            'gender' => (int) $this->gender,
            'dob' => $this->dob,
            'level' => (int) $this->level,
            'is_onboarded' => true
        ]);

        session()->flash('toast', [
            'message' => 'أهلاً بيك في GymZ! 🏋🏽',
            'type' => 'success'
        ]);

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.onboarding');
    }
}
