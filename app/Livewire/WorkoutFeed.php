<?php

namespace App\Livewire;

use App\Enums\RequestStatus;
use App\Models\WorkoutIntent;
use App\Models\WorkoutRequest;
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

    public function sendRequest(int $intentId): void
    {
        $intent = WorkoutIntent::findOrFail($intentId);

        // Prevent sending request to own intent
        if ($intent->user_id === Auth::id()) {
            return;
        }

        // Prevent duplicate requests
        $exists = WorkoutRequest::where('intent_id', $intentId)
            ->where('sender_id', Auth::id())
            ->exists();

        if ($exists) {
            return;
        }

        WorkoutRequest::create([
            'intent_id' => $intentId,
            'sender_id' => Auth::id(),
            'status' => RequestStatus::Pending,
        ]);

        $this->dispatch('request-sent');
    }

    public function render()
    {
        $user = Auth::user();

        // Get IDs of intents the user already sent requests to
        $sentRequestIntentIds = WorkoutRequest::where('sender_id', $user->id)
            ->pluck('intent_id')
            ->toArray();

        $intents = WorkoutIntent::with(['user', 'gym', 'workoutTarget'])
            ->active()
            ->upcoming()
            ->whereHas('user', function ($q) use ($user) {
                $q->where('gender', $user->gender);
            })
            ->where('user_id', '!=', $user->id)
            ->orderBy('start_time', 'asc')
            ->get();

        return view('livewire.workout-feed', [
            'intents' => $intents,
            'sentRequestIntentIds' => $sentRequestIntentIds,
        ]);
    }
}
