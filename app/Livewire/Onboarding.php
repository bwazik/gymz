<?php

namespace App\Livewire;

use App\Enums\Gender;
use App\Enums\UserLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
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
            'phone.required' => 'لازم تكتب رقم موبايلك',
            'phone.regex' => 'رقم الموبايل مش صحيح (لازم يبدأ بـ 010/011/012/015)',
            'phone.max' => 'رقم الموبايل كتير أوي!',
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
        try {
            $this->validate([
                'phone' => ['required', 'string', 'max:11', 'regex:/^(011|010|012|015)\d{8}$/'],
                'gender' => ['required', new Enum(Gender::class)],
                'dob' => ['required', 'date', 'before:today'],
                'level' => ['required', new Enum(UserLevel::class)],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
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
            'message' => 'أهلاً بيك في GymZ! 🚀',
            'type' => 'success'
        ]);

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.onboarding');
    }
}
