<?php

namespace App\Livewire;

use App\Actions\Auth\CompleteOnboarding;
use App\Actions\User\UpdateProfilePhoto;
use App\Enums\Gender;
use App\Enums\UserLevel;
use App\Models\User;
use App\Rules\PhoneNumber;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Onboarding extends Component
{
    use WithToast, WithRateLimiting, WithFileUploads;

    public string $phone = '';
    public string $gender = '';
    public $dob = '';
    public string $level = '';
    public $photo;

    public function messages(): array
    {
        return [
            'phone.unique' => 'رقم الموبايل متسجل قبل كده',
            'phone.required' => 'لازم تكتب رقم موبايلك',
            'gender.required' => 'حدد النوع عشان نقدر نوجهك صح',
            'dob.required' => 'تاريخ ميلادك مهم للتوثيق',
            'dob.before' => 'مش معقول تكون اتولدت في المستقبل! 😄',
            'level.required' => 'حدد مستواك عشان نضبط لك التمرين',
            'photo.image' => 'الملف لازم يكون صورة',
            'photo.mimes' => 'الصورة لازم تكون بصيغة jpeg, png, أو webp',
            'photo.max' => 'حجم الصورة لازم ما يتخطاش 3 ميجابايت',
        ];
    }

    public function mount()
    {
        if (Auth::user()->is_onboarded) {
            return redirect()->route('home');
        }
    }

    public function save(CompleteOnboarding $action, UpdateProfilePhoto $updatePhotoAction)
    {
        if ($this->isRateLimited('onboarding', 3)) {
            return;
        }

        try {
            $this->validate([
                'phone' => ['required', 'string', 'max:255', new PhoneNumber(), Rule::unique(User::class)->ignore(Auth::id())],
                'gender' => ['required', new Enum(Gender::class)],
                'dob' => ['required', 'date', 'before:today'],
                'level' => ['required', new Enum(UserLevel::class)],
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:3072'],
            ]);

            /** @var \App\Models\User $user */
            $user = Auth::user();

            $action->execute($user, [
                'phone' => $this->phone,
                'gender' => $this->gender,
                'dob' => $this->dob,
                'level' => $this->level,
                'photo' => $this->photo,
            ], $updatePhotoAction);

        } catch (ValidationException $e) {
            $this->toastError(collect($e->errors())->flatten()->first());
            throw $e;
        }

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
