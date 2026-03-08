<?php

namespace App\Livewire;

use App\Actions\Workout\CreateWorkoutIntent;
use App\Livewire\Forms\IntentForm;
use App\Models\Gym;
use App\Models\WorkoutCategory;
use App\Models\WorkoutTarget;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateIntent extends Component
{
    use WithToast, WithRateLimiting;
    public IntentForm $form;

    public bool $showModal = false;
    public Collection $gyms;
    public Collection $categories;
    public Collection $targets;

    #[On('open-modal')]
    public function openModal(): void
    {
        $this->showModal = true;
    }

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

    public function save(CreateWorkoutIntent $action): void
    {
        if ($this->isRateLimited('create-intent', 3)) {
            return;
        }

        try {
            $this->form->validate();
            $action->execute([
                'gym_id' => $this->form->gym_id,
                'workout_category_id' => $this->form->workout_category_id,
                'workout_target_id' => $this->form->workout_target_id,
                'start_time' => $this->form->start_time,
                'has_invitation' => $this->form->has_invitation,
                'note' => $this->form->note ?: null,
            ]);
            $this->form->reset();
        } catch (ValidationException $e) {
            $this->toastError(collect($e->errors())->flatten()->first());
            throw $e;
        }

        $this->dispatch('intent-created');
        $this->targets = collect();
        $this->showModal = false;

        $this->toastSuccess('التمرينة اتنشرت بنجاح! 🔥');
    }

    public function render()
    {
        return view('livewire.create-intent');
    }
}
