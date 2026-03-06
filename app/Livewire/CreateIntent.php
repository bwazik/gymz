<?php

namespace App\Livewire;

use App\Livewire\Forms\IntentForm;
use App\Models\Gym;
use App\Models\WorkoutCategory;
use App\Models\WorkoutTarget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class CreateIntent extends Component
{
    public IntentForm $form;

    public bool $showModal = false;
    public Collection $gyms;
    public Collection $categories;
    public Collection $targets;

    public function mount(): void
    {
        $this->gyms = Gym::where('is_active', true)->orderBy('name')->get();
        $this->categories = WorkoutCategory::orderBy('name')->get();
        $this->targets = collect();
    }

    public function updatedFormWorkoutCategoryId($value): void
    {
        $this->targets = $value
            ? WorkoutTarget::where('workout_category_id', $value)->orderBy('name')->get()
            : collect();

        $this->form->workout_target_id = '';
    }

    public function save(): void
    {
        $key = 'create-intent:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "نشرت تمارين كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }
        RateLimiter::hit($key, 60);

        $this->form->store();

        $this->dispatch('intent-created');
        $this->targets = collect();
        $this->showModal = false;

        $this->dispatch('toast', message: 'تمرينة اتنشرت بنجاح! 🔥', type: 'success');
    }

    public function render()
    {
        return view('livewire.create-intent');
    }
}
