<?php

namespace App\Livewire;

use App\Enums\SessionStatus;
use App\Enums\TransactionType;
use App\Models\GlutesTransaction;
use App\Models\User;
use App\Models\WorkoutSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SessionManager extends Component
{
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

    public function verifyToken(string $token, int $sessionId): void
    {
        $key = 'verify-token:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "محاولات كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }
        RateLimiter::hit($key, 60);

        $session = WorkoutSession::findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        // Ensure session is still Scheduled
        if ($session->status !== SessionStatus::Scheduled) {
            $this->dispatch('toast', message: 'التمرين دا مش في حالة مجدولة', type: 'error');
            return;
        }

        $sessionStartTime = $session->workoutIntent->start_time;
        if (now() < $sessionStartTime->copy()->subMinutes(15)) {
            $this->dispatch('toast', message: 'لسه بدري! تقدر تعمل سكان قبل التمرينة بـ 15 دقيقة بس.', type: 'error');
            return;
        }

        if ($token !== $session->qr_token) {
            $this->dispatch('toast', message: 'الكود مش صح. جرب تاني.', type: 'error');
            return;
        }

        DB::transaction(function () use ($session) {
            $session->update([
                'status' => SessionStatus::InProgress,
                'scanned_at' => now(),
            ]);

            // TODO: [NOTIFICATION] - Notify BOTH users that the session has officially started
        });

        unset($this->activeSessions);
        $this->dispatch('token-verified');
        $this->dispatch('toast', message: 'تم التحقق بنجاح! 🔥 يلا بينا', type: 'success');
    }

    public function endSession(int $sessionId): void
    {
        $key = 'end-session:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "محاولات كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }
        RateLimiter::hit($key, 60);

        $session = WorkoutSession::with('workoutIntent.gym')->findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        if ($session->status !== SessionStatus::InProgress) {
            return;
        }

        // ANTI-CHEAT: Minimum 90 minutes
        if ($session->scanned_at && $session->scanned_at->diffInMinutes(now(), false) < 90) {
            $this->dispatch('toast', message: 'التمرينة لازم تكون ٩٠ دقيقة على الأقل عشان تاخد النقط 🏋️', type: 'error');
            return;
        }

        DB::transaction(function () use ($session) {
            $session->update(['status' => SessionStatus::Completed]);

            $gymName = $session->workoutIntent->gym->name;
            $description = "تمرينة في {$gymName}";

            foreach ([$session->user_a_id, $session->user_b_id] as $userId) {
                User::where('id', $userId)->increment('glutes_balance', 10);

                User::where('id', $userId)->update([
                    'reliability_score' => DB::raw('LEAST(100, reliability_score + 3)')
                ]);

                GlutesTransaction::create([
                    'user_id' => $userId,
                    'type' => TransactionType::Earned,
                    'amount' => 10,
                    'description' => $description,
                ]);
            }

            // TODO: [NOTIFICATION] - Notify BOTH users of the reward - 10 Glutes & Reliability increase
        });

        unset($this->activeSessions);
        $this->dispatch('session-completed');
        $this->dispatch('toast', message: 'تم استلام ١٠ جلوتس 🍑 عاش يا بطل!', type: 'success');
    }

    public function reportNoShow(int $sessionId): void
    {
        $key = 'report-noshow:' . Auth::id();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->dispatch('toast', message: "محاولات كتير! استنى {$seconds} ثانية ⏳", type: 'error');
            return;
        }
        RateLimiter::hit($key, 60);

        $session = WorkoutSession::with('workoutIntent')->findOrFail($sessionId);

        // Ensure user is part of this session
        if ($session->user_a_id !== Auth::id() && $session->user_b_id !== Auth::id()) {
            return;
        }

        if ($session->status !== SessionStatus::Scheduled) {
            $this->dispatch('toast', message: 'التمرين دا مش مجدول', type: 'error');
            return;
        }

        if (!$session->workoutIntent) {
            $this->dispatch('toast', message: 'التمرينة مش موجودة', type: 'error');
            return;
        }

        $sessionStartTime = $session->workoutIntent->start_time;
        if (now() < $sessionStartTime->copy()->addMinutes(15)) {
            $this->dispatch('toast', message: 'لازم تستنى 15 دقيقة من ميعاد التمرينة', type: 'error');
            return;
        }

        $absentUserId = ($session->user_a_id === Auth::id()) ? $session->user_b_id : $session->user_a_id;

        DB::transaction(function () use ($session, $absentUserId) {
            User::where('id', $absentUserId)->update([
                'reliability_score' => DB::raw('GREATEST(0, reliability_score - 5)')
            ]);

            $session->update(['status' => SessionStatus::Missed]);

            // TODO: [NOTIFICATION] - Notify the ABSENT user about the penalty and the REPORTER that it's processed
        });

        unset($this->activeSessions);
        $this->dispatch('toast', message: 'تم الإبلاغ وإلغاء التمرينة.. حقك علينا!', type: 'success');
    }

    public function render()
    {
        return view('livewire.session-manager');
    }
}
