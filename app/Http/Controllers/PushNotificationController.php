<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class PushNotificationController extends Controller
{
    /**
     * Handle the incoming FCM token from the frontend and store it against the user.
     */
    public function updateToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $request->user()->update(['fcm_token' => $request->token]);

        return response()->json(['message' => 'Token saved successfully']);
    }

    /**
     * Trigger a mock push notification to the authenticated user for testing purposes.
     */
    public function testPush(Request $request)
    {
        if (!$request->user()->fcm_token) {
            return "You don't have an FCM token saved.";
        }

        $message = CloudMessage::new()
            ->withToken($request->user()->fcm_token)
            ->withNotification(FcmNotification::create('GymZ Time! 🏋️‍♂️', 'Your mock push notification has arrived!'))
            ->withData(['url' => '/', 'type' => 'test']);

        app('firebase.messaging')->send($message);

        return "Push sent to: " . $request->user()->fcm_token;
    }
}
