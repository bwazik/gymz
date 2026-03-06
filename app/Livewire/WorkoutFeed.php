<?php

namespace App\Livewire;

use App\Enums\RequestStatus;
use App\Models\User;
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

        if ($intent->user_id === Auth::id()) {
            return;
        }

        $alreadySent = WorkoutRequest::where('intent_id', $intentId)
            ->where('sender_id', Auth::id())
            ->exists();

        if ($alreadySent) {
            $this->dispatch('toast', message: 'بعت طلب قبل كدى', type: 'error');
            return;
        }

        WorkoutRequest::create([
            'intent_id' => $intentId,
            'sender_id' => Auth::id(),
            'status' => RequestStatus::Pending,
        ]);

        $this->dispatch('request-sent');
        $this->dispatch('toast', message: 'تم إرسال الطلب بنجاح! 💪', type: 'success');
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.workout-feed', [
            'intents' => $this->getUpcomingIntents($user),
            'sentRequestIntentIds' => $this->getSentRequestIntentIds($user),
        ]);
    }

    private function getUpcomingIntents(User $user)
    {
        return WorkoutIntent::with(['user', 'gym', 'workoutTarget'])
            ->active()
            ->upcoming()
            ->whereHas('user', fn($q) => $q->where('gender', $user->gender))
            ->where('user_id', '!=', $user->id)
            ->orderBy('start_time', 'asc')
            ->get();
    }

    private function getSentRequestIntentIds(User $user): array
    {
        return WorkoutRequest::where('sender_id', $user->id)
            ->pluck('intent_id')
            ->toArray();
    }
}
