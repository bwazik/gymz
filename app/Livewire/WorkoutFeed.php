<?php

namespace App\Livewire;

use App\Models\WorkoutIntent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkoutFeed extends Component
{
    #[On('intent-created')]
    public function refreshFeed(): void
    {
        // Re-render is triggered automatically by Livewire
    }
    public function render()
    {
        $intents = WorkoutIntent::with(['user', 'gym', 'workoutTarget'])
            ->active()
            ->upcoming()
            ->whereHas('user', function ($q) {
                $q->where('gender', Auth::user()->gender);
            })
            ->where('user_id', '!=', Auth::id())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('livewire.workout-feed', [
            'intents' => $intents,
        ]);
    }
}
