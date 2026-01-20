<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // NOTE: We do NOT require 'auth' middleware, so guests can verify.
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::where('id', $request->route('id'))->first();

        if (! $user || ! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        // If someone else is logged in, log them out first
        if (Auth::check() && Auth::id() !== $user->id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath())
                ->with('info', 'Your email was already verified. Please login to continue.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Redirect to login page with success message
        return redirect($this->redirectPath())
            ->with('success', 'Your email has been verified successfully! Please login to continue.');
    }
}
