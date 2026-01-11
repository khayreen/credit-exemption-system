<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class LoginSecurityController extends Controller
{
    /**
     * Show the 2FA setup or verification form.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show2faForm(Request $request)
    {
        $userId = $request->session()->get('2fa_user_id');

        if (!$userId) {
            return redirect('/login');
        }

        $user = User::findOrFail($userId);

        if (!$user->google2fa_secret) {
            // Use Google2FA to generate the secret key
            $google2fa = new Google2FA();
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();

            // Generate the QR code URL (text format)
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $user->google2fa_secret
            );

            // Generate the actual QR code SVG image from the URL
            $renderer = new ImageRenderer(
                new RendererStyle(250, 0),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeSvg = $writer->writeString($qrCodeUrl);

            return view('google2fa.index', [
                'qrCodeUrl' => $qrCodeSvg,
                'secret' => $user->google2fa_secret
            ]);
        }

        return view('google2fa.verify');
    }

    /**
     * Verify the 2FA code.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify2fa(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|numeric',
        ]);

        $userId = $request->session()->get('2fa_user_id');
        $user = User::findOrFail($userId);

        // Get the OTP and ensure it's properly formatted (6 digits with leading zeros)
        $otp = str_pad($request->input('one_time_password'), 6, '0', STR_PAD_LEFT);

        // Log for debugging (remove in production)
        Log::info('2FA Verification Attempt', [
            'user_id' => $user->id,
            'otp_input' => $otp,
            'has_secret' => !empty($user->google2fa_secret),
            'window' => config('google2fa.window'),
            'timestamp' => now()->toDateTimeString()
        ]);

        try {
            // Use Google2FA directly with larger time window tolerance (8 = ±4 minutes)
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey(
                $user->google2fa_secret,
                $otp,
                8 // Increased window for better time drift tolerance
            );

            Log::info('2FA Verification Result', [
                'user_id' => $user->id,
                'valid' => $valid,
                'window_used' => 8
            ]);

            if ($valid) {
                Auth::login($user);
                $request->session()->forget('2fa_user_id');
                $request->session()->put('2fa_verified', true);

                // Redirect based on user role
                switch ($user->current_role) {
                    case 'student':
                        return redirect()->intended('/student/dashboard');
                    case 'lecturer':
                        return redirect()->intended('/lecturer/dashboard');
                    case 'coordinator':
                        return redirect()->intended('/coordinator/dashboard');
                    case 'program_coordinator':
                        return redirect()->intended('/program-coordinator/dashboard');
                    case 'resource_person':
                        return redirect()->intended('/resource-person/dashboard');
                    case 'external_lecturer':
                        return redirect()->intended('/external-lecturer/dashboard');
                    case 'hea_personnel':
                        return redirect()->intended('/hea/dashboard');
                    default:
                        return redirect()->intended('/home');
                }
            }
        } catch (\Exception $e) {
            Log::error('2FA Verification Exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('2fa.index')->with('error', 'An error occurred during verification. Please try again.');
        }

        return redirect()->route('2fa.index')->with('error', 'Invalid 2FA code. Please try again.');
    }
}
