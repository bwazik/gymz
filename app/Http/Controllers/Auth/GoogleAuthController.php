<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\User\ReferralJoinedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        if ($request->has('ref')) {
            $request->session()->put('referral_code', $request->query('ref'));
        }
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $referredBy = null;
                $refCode = request()->session()->pull('referral_code');

                if ($refCode) {
                    $referrer = User::where('referral_code', $refCode)->first();
                    if ($referrer) {
                        $referredBy = $referrer->id;
                    }
                }

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'is_onboarded' => false,
                    'password' => null,
                    'image_path' => $googleUser->getAvatar(),
                    'referred_by' => $referredBy,
                ]);

                if ($referredBy) {
                    if ($referrerUser = User::find($referredBy)) {
                        $referrerUser->notify(new ReferralJoinedNotification($user));
                    }
                }
            } elseif (!$user->google_id) {
                // Link account if they previously signed up without Google (or if we import users)
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'image_path' => $user->image_path ?? $googleUser->getAvatar()
                ]);
            }

            Auth::login($user);

            if (!$user->is_onboarded) {
                return redirect()->route('onboarding');
            }

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect('/')->with('error', 'حصلت مشكلة في تسجيل الدخول بجوجل: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
