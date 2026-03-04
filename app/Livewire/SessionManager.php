<?php

namespace App\Livewire;

use App\Enums\SessionStatus;
use App\Enums\TransactionType;
use App\Models\GlutesTransaction;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SessionManager extends Component
{
    public string $scannedToken = '';

    #[Computed]
    public function activeSessions()
    {
        return WorkoutSession::with(['workoutIntent.gym', 'userA', 'userB'])
            ->where(function ($q) {
                $q->where('user_a_id', Auth::id())
                    ->orWhere('user_b_id', Auth::id());
            })
            ->whereIn('status', [SessionStatus::Scheduled, SessionStatus::InProgress])
            ->latest()
            ->get();
    }

    public function verifyToken(int $sessionId): void
    {
        $session = WorkoutSession::findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        if ($this->scannedToken === $session->qr_token) {
            $session->update([
                'status' => SessionStatus::InProgress,
                'scanned_at' => now(),
            ]);

            $this->scannedToken = '';
            unset($this->activeSessions);
            $this->dispatch('token-verified');
        } else {
            $this->addError('scannedToken', 'Invalid token. Please try again.');
        }
    }

    public function endSession(int $sessionId): void
    {
        $session = WorkoutSession::with('workoutIntent.gym')->findOrFail($sessionId);

        // Ensure user is part of this session and it's in progress
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        if ($session->status !== SessionStatus::InProgress) {
            return;
        }

        // Mark session as completed
        $session->update(['status' => SessionStatus::Completed]);

        $gymName = $session->workoutIntent->gym->name;
        $description = "Completed workout at {$gymName}";

        // Reward both users with 10 Glutes
        foreach ([$session->user_a_id, $session->user_b_id] as $userId) {
            \App\Models\User::where('id', $userId)->increment('glutes_balance', 10);

            GlutesTransaction::create([
                'user_id' => $userId,
                'type' => TransactionType::Earned,
                'amount' => 10,
                'description' => $description,
            ]);
        }

        unset($this->activeSessions);
        $this->dispatch('session-completed');
    }

    public function render()
    {
        return view('livewire.session-manager');
    }
}
