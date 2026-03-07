<?php

namespace App\Livewire;

use App\Actions\Workout\AcceptWorkoutRequest;
use App\Actions\Workout\CancelSentWorkoutRequest;
use App\Actions\Workout\RejectWorkoutRequest;
use App\Enums\RequestStatus;
use App\Models\WorkoutRequest;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Exception;

#[Layout('layouts.app')]
class RequestsManager extends Component
{
    use WithToast, WithRateLimiting;

    public string $activeTab = 'incoming';

    #[Computed]
    public function incomingRequests()
    {
        return WorkoutRequest::with(['sender', 'workoutIntent.gym', 'workoutIntent.workoutTarget'])
            ->where('status', RequestStatus::Pending)
            ->whereHas('workoutIntent', function ($q) {
                $q->where('user_id', Auth::id())
                    ->where('start_time', '>=', now());
            })
            ->has('sender')
            ->latest()
            ->get();
    }

    #[Computed]
    public function outgoingRequests()
    {
        return WorkoutRequest::with(['workoutIntent.user', 'workoutIntent.gym', 'workoutIntent.workoutTarget'])
            ->where('user_id', Auth::id())
            ->has('workoutIntent.user')
            ->whereHas('workoutIntent', function ($q) {
                $q->where('start_time', '>=', now());
            })
            ->latest()
            ->get();
    }

    public function acceptRequest(int $requestId, AcceptWorkoutRequest $action): void
    {
        if ($this->isRateLimited('manage-request')) {
            return;
        }

        $request = WorkoutRequest::with('workoutIntent')->findOrFail($requestId);

        // Ensure this request belongs to the current user's intent
        if ($request->workoutIntent->user_id !== Auth::id()) {
            return;
        }

        try {
            $action->execute($request);
            unset($this->incomingRequests);
            $this->toastSuccess('تم قبول الطلب! روح لصفحة التمارين عشان تبدأ 🔥');
        } catch (Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function rejectRequest(int $requestId, RejectWorkoutRequest $action): void
    {
        if ($this->isRateLimited('manage-request')) {
            return;
        }

        $request = WorkoutRequest::findOrFail($requestId);

        // Ensure this request belongs to the current user's intent
        if ($request->workoutIntent->user_id !== Auth::id()) {
            return;
        }

        try {
            $action->execute($request);
            unset($this->incomingRequests);
            $this->toastSuccess('تم رفض الطلب');
        } catch (Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function cancelSentRequest(int $requestId, CancelSentWorkoutRequest $action): void
    {
        if ($this->isRateLimited('manage-request')) {
            return;
        }

        $request = WorkoutRequest::with('workoutIntent')->where('sender_id', Auth::id())->findOrFail($requestId);

        try {
            $message = $action->execute($request);
            unset($this->outgoingRequests);

            if ($message) {
                // Determine if it was pending vs accepted cancelation
                if (str_contains($message, 'مقبول')) {
                    $this->toastError($message);
                } else {
                    $this->toastSuccess($message);
                }
            }
        } catch (Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.requests-manager');
    }
}
