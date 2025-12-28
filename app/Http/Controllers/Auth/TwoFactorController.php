<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA setup page
     */
    public function showSetup()
    {
        // Fresh user from database to avoid caching issues
        $user = \App\Models\User::find(Auth::id());

        // If already verified, redirect to home
        if ($user->two_factor_verified_at) {
            return redirect()->route('home');
        }

        // Generate secret if not exists
        if (!$user->google2fa_secret) {
            $google2fa = new Google2FA();
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        // Log the secret being used for QR code
        \Log::info('2FA Setup Page Loaded', [
            'user_email' => $user->email,
            'secret' => $user->google2fa_secret,
            'qr_code_will_use_secret' => $user->google2fa_secret,
        ]);

        // Generate QR code
        $qrCodeUrl = $this->getQRCodeUrl($user);

        // Pass secret key explicitly
        $secretKey = $user->google2fa_secret;

        return view('auth.2fa-setup', compact('qrCodeUrl', 'secretKey'));
    }

    /**
     * Verify and activate 2FA
     */
    public function verify(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric|digits:6',
        ]);

        // Fresh user from database to avoid caching issues
        $user = \App\Models\User::find(Auth::id());
        $google2fa = new Google2FA();

        // Debug logging
        \Log::info('2FA Verification Attempt', [
            'user_email' => $user->email,
            'secret' => $user->google2fa_secret,
            'entered_code' => $request->one_time_password,
            'server_time' => time(),
            'expected_code' => $google2fa->getCurrentOtp($user->google2fa_secret),
        ]);

        // Add time window tolerance (8 = 4 minutes before/after)
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->one_time_password, 8);

        if (!$valid) {
            \Log::warning('2FA Verification Failed', [
                'user_email' => $user->email,
                'entered_code' => $request->one_time_password,
                'expected_code' => $google2fa->getCurrentOtp($user->google2fa_secret),
            ]);
            return back()->withErrors(['one_time_password' => 'The verification code is incorrect. Please ensure your device time is synchronized and you scanned the correct QR code.']);
        }

        // Mark 2FA as verified
        $user->two_factor_verified_at = now();
        $user->save();

        // User stays logged in and goes directly to home
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
        $otp = $request->one_time_password;

        // Verify OTP with time window tolerance (8 = 4 minutes before/after)
        $valid = $google2fa->verifyKey($user->google2fa_secret, $otp, 8);

        if (!$valid) {
            return back()->withErrors(['one_time_password' => 'The verification code is incorrect.']);
        }

        // Login successful
        session()->forget('2fa_user_id');
        Auth::login($user, true);

        return redirect()->intended('/home')->with('success', 'Login successful!');
    }

}
