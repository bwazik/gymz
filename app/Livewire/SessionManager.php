<?php

namespace App\Livewire;

use App\Actions\Workout\EndWorkoutSession;
use App\Actions\Workout\ReportSessionNoShow;
use App\Actions\Workout\VerifySessionToken;
use App\Enums\SessionStatus;
use App\Models\WorkoutSession;
use App\Traits\Livewire\WithRateLimiting;
use App\Traits\Livewire\WithToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Exception;

#[Layout('layouts.app')]
class SessionManager extends Component
{
    use WithToast, WithRateLimiting;
    #[Computed]
    public function activeSessions()
    {
        return WorkoutSession::with(['workoutIntent' => fn($q) => $q->withTrashed()->with('gym'), 'userA', 'userB'])
            ->where(function ($q) {
                $q->where('user_a_id', Auth::id())
                    ->orWhere('user_b_id', Auth::id());
            })
            ->whereIn('status', [SessionStatus::Scheduled, SessionStatus::InProgress])
            ->whereHas('workoutIntent', function ($q) {
                $q->whereNull('deleted_at'); // STRICT: Intent must not be deleted
            })
            ->latest()
            ->get();
    }

    public function verifyToken(string $token, int $sessionId, VerifySessionToken $action): void
    {
        if ($this->isRateLimited('verify-token')) {
            return;
        }

        $session = WorkoutSession::findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        try {
            $action->execute($session, $token);
            unset($this->activeSessions);
            $this->dispatch('token-verified');
            $this->toastSuccess('تم التحقق بنجاح! 🔥 يلا بينا');
        } catch (Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function endSession(int $sessionId, EndWorkoutSession $action): void
    {
        if ($this->isRateLimited('end-session', 3)) {
            return;
        }

        $session = WorkoutSession::with('workoutIntent.gym')->findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        try {
            $action->execute($session);
            unset($this->activeSessions);
            $this->dispatch('session-completed');
            $this->toastSuccess('تم استلام ١٠ جلوتس 🍑 عاش يا بطل!');
        } catch (Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function reportNoShow(int $sessionId, ReportSessionNoShow $action): void
    {
        if ($this->isRateLimited('report-noshow', 3)) {
            return;
        }

        $session = WorkoutSession::with('workoutIntent')->findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        try {
            $action->execute($session, Auth::id());
            unset($this->activeSessions);
            $this->toastSuccess('تم الإبلاغ وإلغاء التمرينة.. حقك علينا!');
        } catch (Exception $e) {
            $this->toastError($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.session-manager');
    }
}
