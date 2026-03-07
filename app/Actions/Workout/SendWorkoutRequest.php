<?php

namespace App\Actions\Workout;

use App\Enums\RequestStatus;
use App\Models\WorkoutIntent;
use App\Models\WorkoutRequest;
use Exception;
use Illuminate\Support\Facades\Auth;

class SendWorkoutRequest
{
    /**
     * Sends a request to join a workout intent.
     *
     * @param int $intentId
     * @return string The success message
     * @throws Exception
     */
    public function execute(int $intentId): string
    {
        $intent = WorkoutIntent::findOrFail($intentId);

        if ($intent->user_id === Auth::id()) {
            throw new Exception('متقدرش تبعت طلب لتمرينتك!');
        }

        $alreadySent = WorkoutRequest::where('intent_id', $intentId)
            ->where('sender_id', Auth::id())
            ->exists();

        if ($alreadySent) {
            throw new Exception('بعت طلب قبل كدى');
        }

        WorkoutRequest::create([
            'intent_id' => $intentId,
            'sender_id' => Auth::id(),
            'status' => RequestStatus::Pending,
        ]);

        // TODO: [NOTIFICATION] - Notify HOST that a new workout request was received

        return 'تم إرسال الطلب بنجاح! 💪';
    }
}
