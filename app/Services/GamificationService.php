<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\GlutesTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
}
