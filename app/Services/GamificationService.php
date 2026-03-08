<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\GlutesTransaction;
use App\Models\User;
use App\Notifications\Admin\WelcomeBonusAdminNotification;
use App\Notifications\User\WelcomeBonusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GamificationService
{
    /**
     * Deduct reliability score from a user.
     * Ensures the score never drops below 0.
     *
     * @param int $userId
     * @param int $amount
     * @return void
     */
    public function deductReliability(int $userId, int $amount = 5): void
    {
        User::where('id', $userId)->update([
            'reliability_score' => DB::raw("GREATEST(0, reliability_score - {$amount})")
        ]);
    }

    /**
     * Add reliability score to a user.
     * Ensures the score never exceeds 100.
     *
     * @param int $userId
     * @param int $amount
     * @return void
     */
    public function addReliability(int $userId, int $amount = 3): void
    {
        User::where('id', $userId)->update([
            'reliability_score' => DB::raw("LEAST(100, reliability_score + {$amount})")
        ]);
    }

    /**
     * Award glutes to a user and log the transaction.
     *
     * @param int $userId
     * @param int $amount
     * @param string $description
     * @return void
     */
    public function awardGlutes(int $userId, int $amount, string $description): void
    {
        DB::transaction(function () use ($userId, $amount, $description) {
            User::where('id', $userId)->increment('glutes_balance', $amount);

            GlutesTransaction::create([
                'user_id' => $userId,
                'type' => TransactionType::Earned,
                'amount' => $amount,
                'description' => $description,
            ]);
        });
    }

    /**
     * Deduct glutes from a user (for future use: purchasing/withdrawing).
     *
     * @param int $userId
     * @param int $amount
     * @param string $description
     * @return bool
     */
    public function deductGlutes(int $userId, int $amount, string $description): bool
    {
        $user = User::find($userId);

        if (!$user || $user->glutes_balance < $amount) {
            return false;
        }

        DB::transaction(function () use ($user, $amount, $description) {
            $user->decrement('glutes_balance', $amount);

            GlutesTransaction::create([
                'user_id' => $user->id,
                'type' => TransactionType::Spent,
                'amount' => $amount,
                'description' => $description,
            ]);
        });

        return true;
    }

    /**
     * Process referral rewards for a new user's first completed session.
     * Both the newbie and the referrer get 50 Glutes.
     *
     * @param User $newbie
     * @return void
     */
    public function processReferralReward(User $newbie): void
    {
        if (!$newbie->referred_by) {
            return;
        }

        $referrer = User::find($newbie->referred_by);
        if (!$referrer) {
            return;
        }

        DB::transaction(function () use ($newbie, $referrer) {
            // Reward Newbie
            $this->awardGlutes(
                $newbie->id,
                10,
                "بونص ترحيب: أول تمرينة مع GymZ 🏋🏽"
            );

            $newbie->notify(new WelcomeBonusNotification(10));
            Notification::send(User::admin()->get(), new WelcomeBonusAdminNotification(10, $newbie->name));

            // Reward Referrer
            $this->awardGlutes(
                $referrer->id,
                10,
                "مكافأة دعوة: {$newbie->name} نزل أول تمرينة 🤝"
            );
        });
    }
}
