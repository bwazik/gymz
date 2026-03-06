<?php

namespace App\Livewire;

use App\Enums\IntentStatus;
use App\Enums\RequestStatus;
use App\Enums\SessionStatus;
use App\Models\WorkoutRequest;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class RequestsManager extends Component
{
    public string $activeTab = 'incoming';

    #[Computed]
    public function incomingRequests()
    {
        return WorkoutRequest::with(['sender', 'workoutIntent.gym', 'workoutIntent.workoutTarget'])
            ->where('status', RequestStatus::Pending)
            ->whereHas('workoutIntent', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->has('sender')
            ->latest()
            ->get();
    }

    #[Computed]
    public function outgoingRequests()
    {
        return WorkoutRequest::with(['workoutIntent.user', 'workoutIntent.gym', 'workoutIntent.workoutTarget'])
            ->where('sender_id', Auth::id())
            ->has('workoutIntent.user')
            ->latest()
            ->get();
    }

    public function acceptRequest(int $requestId): void
    {
        $key = 'manage-request:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "حاولت كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }
        RateLimiter::hit($key, 60);

        $request = WorkoutRequest::with('workoutIntent')->findOrFail($requestId);

        // Ensure this request belongs to the current user's intent
        if ($request->workoutIntent->user_id !== Auth::id()) {
            return;
        }

        DB::transaction(function () use ($request) {
            // Accept this request
            $request->update(['status' => RequestStatus::Accepted]);

            // Mark intent as Matched
            $request->workoutIntent->update(['status' => IntentStatus::Matched]);

            // Reject all other pending requests for this intent
            WorkoutRequest::where('intent_id', $request->intent_id)
                ->where('id', '!=', $request->id)
                ->where('status', RequestStatus::Pending)
                ->update(['status' => RequestStatus::Rejected]);

            // Create a WorkoutSession
            WorkoutSession::create([
                'intent_id' => $request->intent_id,
                'user_a_id' => $request->workoutIntent->user_id,
                'user_b_id' => $request->sender_id,
                'qr_token' => Str::random(32),
                'status' => SessionStatus::Scheduled,
            ]);
        });

        unset($this->incomingRequests);
        $this->dispatch('toast', message: 'تم قبول الطلب! الجلسة اتعملت 🔥', type: 'success');
    }

    public function rejectRequest(int $requestId): void
    {
        $key = 'manage-request:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "حاولت كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }
        RateLimiter::hit($key, 60);

        $request = WorkoutRequest::findOrFail($requestId);

        // Ensure this request belongs to the current user's intent
        if ($request->workoutIntent->user_id !== Auth::id()) {
            return;
        }

        $request->update(['status' => RequestStatus::Rejected]);

        unset($this->incomingRequests);
        $this->dispatch('toast', message: 'تم رفض الطلب', type: 'success');
    }

    public function render()
    {
        return view('livewire.requests-manager');
    }
}
