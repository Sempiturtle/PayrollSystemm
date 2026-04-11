<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->stateless()
                ->user();
            
            // Security Check: Only allow login if the email exists in our system (pre-registered by admin)
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Access denied. Your account must be pre-registered by an administrator using your Gmail address.',
                ]);
            }

            // Mark as verified since Google has already verified the email
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user);

            return redirect()->intended(route('dashboard', absolute: false));

        } catch (Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Google Authentication failed. Please try again.',
            ]);
        }
    }
}
