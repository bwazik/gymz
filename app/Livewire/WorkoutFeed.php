<?php

namespace App\Livewire;

use App\Actions\Workout\SendWorkoutRequest;
use App\Models\User;
use App\Models\Gym;
use App\Models\WorkoutCategory;
use App\Models\WorkoutIntent;
use App\Models\WorkoutRequest;
use App\Models\WorkoutTarget;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkoutFeed extends Component
{
    use WithToast, WithRateLimiting;

    public ?int $gymFilter = null;
    public ?int $categoryFilter = null;
    public ?int $targetFilter = null;
    #[On('intent-created')]
    public function refreshFeed(): void
    {
        // Re-render is triggered automatically by Livewire
    }

    public function sendRequest(int $intentId, SendWorkoutRequest $action): void
    {
        if ($this->isRateLimited('send-request', 5, 30)) {
            return;
        }

        try {
            $message = $action->execute($intentId);
            $this->dispatch('request-sent');
            $this->toastSuccess($message);
        } catch (\Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['gymFilter', 'categoryFilter', 'targetFilter']);
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.workout-feed', [
            'intents' => $this->getUpcomingIntents($user),
            'sentRequestIntentIds' => $this->getSentRequestIntentIds($user),
            'targets' => WorkoutTarget::all(),
            'categories' => WorkoutCategory::orderBy('name')->get(),
            'gyms' => Gym::active()->orderBy('name')->get(),
        ]);
    }

    private function getUpcomingIntents(?User $user = null)
    {
        $query = WorkoutIntent::with(['user', 'gym', 'workoutTarget'])
            ->active()
            ->upcoming();

        // Target Filter
        $query->when($this->targetFilter, function ($q) {
            $q->where('workout_target_id', $this->targetFilter);
        });

        // Category Filter
        $query->when($this->categoryFilter, function ($q) {
            $q->whereHas('workoutTarget', function ($sub) {
                $sub->where('workout_category_id', $this->categoryFilter);
            });
        });

        // Gym Filter
        $query->when($this->gymFilter, function ($q) {
            $q->where('gym_id', $this->gymFilter);
        });

        if ($user && $user->gender) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('gender', $user->gender);
            });
            $query->where('user_id', '!=', $user->id);
        }

        return $query->orderBy('start_time', 'asc')->get();
    }

    private function getSentRequestIntentIds(?User $user = null): array
    {
        if (!$user) {
            return [];
        }

        return WorkoutRequest::where('sender_id', $user->id)
            ->pluck('intent_id')
            ->toArray();
    }
}
