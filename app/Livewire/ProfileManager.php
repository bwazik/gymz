<?php

namespace App\Livewire;

use App\Actions\Workout\CancelWorkoutIntent;
use App\Models\User;
use App\Models\WorkoutSession;
use App\Rules\PhoneNumber;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception;

#[Layout('layouts.app')]
class ProfileManager extends Component
{
    use WithFileUploads, WithToast, WithRateLimiting;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $photo;
    public bool $showHistoryModal = false;

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

    public function updateProfileInformation(): void
    {
        if ($this->isRateLimited('update-profile', 3)) {
            return;
        }

        /** @var \App\Models\User $user */
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
            $this->toastError($firstError);
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

        $this->toastSuccess('تم تحديث البيانات بنجاح!');
        $this->photo = null;
    }

    public function deleteIntent(int $intentId, CancelWorkoutIntent $action): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $intent = $user->workoutIntents()->find($intentId);

        if (!$intent) {
            return;
        }

        try {
            $message = $action->execute($intent);

            if (str_contains($message, 'مؤكد')) {
                $this->toastError($message);
            } else {
                $this->toastSuccess($message);
            }
        } catch (Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    #[Computed]
    public function historyItems(): Collection
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = $user->id;

        // 1. Intents where user is host, time passed, NO session exists
        $intentsWithoutSessions = $user
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
        $sessions = WorkoutSession::with(['workoutIntent' => fn($q) => $q->withTrashed()->with(['gym', 'workoutTarget'])])
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

    public function sendVerification(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('livewire.profile-manager', [
            'user' => $user,
            'activeIntents' => $user->workoutIntents()->where('start_time', '>=', now())->orderBy('start_time')->get()
        ]);
    }
}
