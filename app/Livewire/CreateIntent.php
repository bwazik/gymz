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

    #[Validate([
        'gym_id' => 'required|integer|exists:gyms,id',
    ], message: [
        'gym_id.required' => 'لازم تختار الجيم',
        'gym_id.integer' => 'الجيم مش صح',
        'gym_id.exists' => 'الجيم ده مش موجود',
    ])]
    public $gym_id = '';

    #[Validate([
        'workout_category_id' => 'required|integer|exists:workout_categories,id',
    ], message: [
        'workout_category_id.required' => 'لازم تختار نوع التمرين',
        'workout_category_id.integer' => 'نوع التمرين مش صح',
        'workout_category_id.exists' => 'نوع التمرين ده مش موجود',
    ])]
    public $workout_category_id = '';

    #[Validate([
        'workout_target_id' => 'required|integer|exists:workout_targets,id',
    ], message: [
        'workout_target_id.required' => 'لازم تختار العضلة',
        'workout_target_id.integer' => 'العضلة مش صح',
        'workout_target_id.exists' => 'العضلة دي مش موجودة',
    ])]
    public $workout_target_id = '';

    #[Validate([
        'start_time' => 'required|date|after:now',
    ], message: [
        'start_time.required' => 'لازم تحدد ميعاد التمرين',
        'start_time.date' => 'الميعاد مش صح',
        'start_time.after' => 'الميعاد لازم يكون في المستقبل',
    ])]
    public $start_time = '';

    #[Validate('boolean')]
    public bool $has_invitation = false;

    #[Validate([
        'note' => 'nullable|string|max:255',
    ], message: [
        'note.max' => 'الملاحظات لازم تكون أقل من ٢٥٥ حرف',
    ])]
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
