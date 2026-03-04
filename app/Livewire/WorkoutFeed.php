<?php

namespace App\Livewire;

use App\Models\WorkoutIntent;
use Livewire\Component;

class WorkoutFeed extends Component
{
    public function render()
    {
        $intents = WorkoutIntent::with(['user', 'gym', 'workoutTarget'])
            ->active()
            ->upcoming()
            ->whereHas('user', function ($q) {
                $q->where('gender', auth()->user()->gender);
            })
            ->where('user_id', '!=', auth()->id())
            ->orderBy('start_time', 'asc')
            ->get();

        return view('livewire.workout-feed', [
            'intents' => $intents,
        ]);
    }
}
