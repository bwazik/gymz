<?php

namespace App\Livewire;

use App\Enums\SessionStatus;
use App\Enums\TransactionType;
use App\Models\GlutesTransaction;
use App\Models\User;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SessionManager extends Component
{
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

    public function verifyToken(string $token, int $sessionId): void
    {
        $session = WorkoutSession::findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        // Ensure session is still Scheduled
        if ($session->status !== SessionStatus::Scheduled) {
            session()->flash('error', 'الجلسة دي مش في حالة مجدولة');
            return;
        }

        if ($token !== $session->qr_token) {
            session()->flash('error', 'الكود مش صح. جرب تاني.');
            return;
        }

        DB::transaction(function () use ($session) {
            $session->update([
                'status' => SessionStatus::InProgress,
                'scanned_at' => now(),
            ]);
        });

        unset($this->activeSessions);
        $this->dispatch('token-verified');
    }

    public function endSession(int $sessionId): void
    {
        $session = WorkoutSession::with('workoutIntent.gym')->findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        if ($session->status !== SessionStatus::InProgress) {
            return;
        }

        // ANTI-CHEAT: Minimum 30 minutes
        if ($session->scanned_at && now()->diffInMinutes($session->scanned_at) < 90) {
            session()->flash('error', 'التمرينة لازم تكون ٩٠ دقيقة على الأقل عشان تاخد النقط 🏋️');
            return;
        }

        DB::transaction(function () use ($session) {
            $session->update(['status' => SessionStatus::Completed]);

            $gymName = $session->workoutIntent->gym->name;
            $description = "تمرينة في {$gymName}";

            foreach ([$session->user_a_id, $session->user_b_id] as $userId) {
                User::where('id', $userId)->increment('glutes_balance', 10);

                GlutesTransaction::create([
                    'user_id' => $userId,
                    'type' => TransactionType::Earned,
                    'amount' => 10,
                    'description' => $description,
                ]);
            }
        });

        unset($this->activeSessions);
        $this->dispatch('session-completed');
    }

    public function render()
    {
        return view('livewire.session-manager');
    }
}
