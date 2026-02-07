<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA setup page
     * Uses session-based user tracking — user is NOT authenticated during setup.
     */
    public function showSetup()
    {
        // Get user ID from session (set by LoginController before redirect)
        $userId = session('2fa_setup_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            session()->forget('2fa_setup_user_id');
            return redirect()->route('login');
        }

        // If already verified, clear session and redirect to login
        if ($user->two_factor_verified_at) {
            session()->forget('2fa_setup_user_id');
            return redirect()->route('login');
        }

        // Generate secret if not exists
        if (!$user->google2fa_secret) {
            $google2fa = new Google2FA();
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        // Generate QR code
        $qrCodeUrl = $this->getQRCodeUrl($user);

        // Pass secret key and email explicitly (user is not authenticated during setup)
        $secretKey = $user->google2fa_secret;
        $userEmail = $user->email;

        return view('auth.2fa-setup', compact('qrCodeUrl', 'secretKey', 'userEmail'));
    }

    /**
     * Verify and activate 2FA
     * Uses session-based user tracking — authenticates user only AFTER OTP is verified.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric|digits:6',
        ]);

        // Get user from session (NOT from Auth)
        $userId = session('2fa_setup_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            session()->forget('2fa_setup_user_id');
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        $google2fa = new Google2FA();

        // Verify the OTP code against the user's secret
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password, 8);

        if (!$valid) {
            \Log::warning('2FA Setup Verification Failed', [
                'user_email' => $user->email,
            ]);
            return back()->withErrors(['one_time_password' => 'The verification code is incorrect. Please ensure your device time is synchronized and you scanned the correct QR code.']);
        }

        // Mark 2FA as verified
        $user->two_factor_verified_at = now();
        $user->save();

        // Clear setup session and authenticate the user
        session()->forget('2fa_setup_user_id');
        Auth::login($user, true);

        return redirect()->route('home')->with('success', 'Two-factor authentication has been enabled successfully! Your account is now secure.');
    }

    /**
     * Generate QR code URL
     */
    private function getQRCodeUrl($user)
    {
        $google2fa = new Google2FA();
        $companyName = config('app.name');
        $companyEmail = $user->email;

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $user->google2fa_secret
        );

        // Generate SVG QR code
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrCodeImage = $writer->writeString($qrCodeUrl);

        return 'data:image/svg+xml;base64,' . base64_encode($qrCodeImage);
    }

    /**
     * Show 2FA verification page during login
     */
    public function showLogin()
    {
        // Check if there's a user trying to authenticate
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        $userId = session('2fa_user_id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            session()->forget('2fa_user_id');
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        return view('auth.2fa-login', ['email' => $user->email]);
    }

    /**
     * Verify 2FA code during login
     */
    public function verifyLogin(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric|digits:6',
        ]);

        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        $userId = session('2fa_user_id');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            session()->forget('2fa_user_id');
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        $google2fa = new Google2FA();
        $otp = trim($request->one_time_password);

        // Verify the OTP code against the user's secret
        $valid = $google2fa->verifyKey($user->google2fa_secret, $otp, 8);

        if (!$valid) {
            \Log::warning('2FA Login Verification Failed', [
                'user_email' => $user->email,
            ]);
            return back()->withErrors(['one_time_password' => 'The verification code is incorrect.']);
        }

        // Login successful
        session()->forget('2fa_user_id');
        Auth::login($user, true);

        return redirect()->intended('/home')->with('success', 'Login successful!');
    }

}
