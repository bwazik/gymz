<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'is_onboarded' => false,
                    'password' => null,
                    'image_path' => $googleUser->getAvatar(),
                ]);
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
            return redirect('/')->with('error', 'حصلت مشكلة في تسجيل الدخول بجوجل.');
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
