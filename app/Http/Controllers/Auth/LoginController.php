<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after authentication.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     * This method is called after a user successfully logs in with their password.
     * We use it to redirect them to email verification or 2FA setup/verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // External lecturers don't use 2FA (they use token-based access)
        if ($user->current_role === 'external_lecturer') {
            return null; // Allow login normally
        }

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            auth()->logout();
            return redirect()->route('login')
                ->with('warning', 'Please verify your email address before logging in. Check your inbox for the verification link.');
        }

        // MANDATORY 2FA for all users (except external lecturers)

        // First login: 2FA not set up yet
        if (!$user->two_factor_verified_at) {
            // Redirect to 2FA setup (stay logged in for setup)
            return redirect()->route('2fa.setup')
                ->with('status', 'Please set up two-factor authentication to secure your account.');
        }

        // Subsequent logins: 2FA already set up
        // Logout and require OTP verification
        auth()->logout();
        $request->session()->put('2fa_user_id', $user->id);
        return redirect()->route('2fa.login');
    }
}
