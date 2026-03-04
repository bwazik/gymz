<?php

namespace App\Livewire;

use App\Enums\IntentStatus;
use App\Models\Gym;
use App\Models\WorkoutCategory;
use App\Models\WorkoutIntent;
use App\Models\WorkoutTarget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateIntent extends Component
{
    public bool $showModal = false;

    #[Validate('required|exists:gyms,id')]
    public $gym_id = '';

    #[Validate('required|exists:workout_categories,id')]
    public $workout_category_id = '';

    #[Validate('required|exists:workout_targets,id')]
    public $workout_target_id = '';

    #[Validate('required|date|after:now')]
    public $start_time = '';

    #[Validate('boolean')]
    public bool $has_invitation = false;

    #[Validate('nullable|string|max:255')]
    public $note = '';

    public Collection $gyms;
    public Collection $categories;
    public Collection $targets;

    public function mount(): void
    {
        $this->gyms = Gym::where('is_active', true)->orderBy('name')->get();
        $this->categories = WorkoutCategory::orderBy('name')->get();
        $this->targets = collect();
    }

    public function updatedWorkoutCategoryId($value): void
    {
        $this->targets = $value
            ? WorkoutTarget::where('workout_category_id', $value)->orderBy('name')->get()
            : collect();

        $this->workout_target_id = '';
    }

    public function save(): void
    {
        $this->validate();

        WorkoutIntent::create([
            'user_id' => Auth::id(),
            'gym_id' => $this->gym_id,
            'workout_category_id' => $this->workout_category_id,
            'workout_target_id' => $this->workout_target_id,
            'start_time' => $this->start_time,
            'has_invitation' => $this->has_invitation,
            'note' => $this->note ?: null,
            'status' => IntentStatus::Active,
        ]);

        $this->dispatch('intent-created');

        $this->reset(['gym_id', 'workout_category_id', 'workout_target_id', 'start_time', 'has_invitation', 'note']);
        $this->targets = collect();
        $this->showModal = false;

        session()->flash('message', 'Workout intent posted! 💪');
    }

    public function render()
    {
        return view('livewire.create-intent');
    }
}
