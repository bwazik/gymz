<?php

namespace App\Livewire;

use App\Enums\RequestStatus;
use App\Models\User;
use App\Models\WorkoutIntent;
use App\Models\WorkoutRequest;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkoutFeed extends Component
{
    use WithToast;
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
            $this->toastError('بعت طلب قبل كدى');
            return;
        }

        WorkoutRequest::create([
            'intent_id' => $intentId,
            'sender_id' => Auth::id(),
            'status' => RequestStatus::Pending,
        ]);

        // TODO: [NOTIFICATION] - Notify HOST that a new workout request was received

        $this->dispatch('request-sent');
        $this->toastSuccess('تم إرسال الطلب بنجاح! 💪');
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.workout-feed', [
            'intents' => $this->getUpcomingIntents($user),
            'sentRequestIntentIds' => $this->getSentRequestIntentIds($user),
        ]);
    }

    private function getUpcomingIntents(?User $user = null)
    {
        $query = WorkoutIntent::with(['user', 'gym', 'workoutTarget'])
            ->active()
            ->upcoming();

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
