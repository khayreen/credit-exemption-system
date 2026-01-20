<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * Attempt to log the user into the application.
     * Overridden to track login attempts and check for locked accounts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $email = $request->input($this->username());
        $user = User::where('email', $email)->first();

        // Check if account is locked
        if ($user && $user->isLocked()) {
            // Log the failed attempt
            LoginAttempt::log($email, 'failed', 'account_locked', $user->id);

            throw ValidationException::withMessages([
                $this->username() => ['Your account has been locked due to too many failed login attempts. Please contact support.'],
            ]);
        }

        // Attempt the login
        $success = $this->guard()->attempt(
            $this->credentials($request), $request->boolean('remember')
        );

        if ($success) {
            // Log successful attempt
            LoginAttempt::log($email, 'success', null, $user->id ?? null);

            // Reset failed login attempts on success
            if ($user) {
                $user->resetFailedLogins();
            }
        } else {
            // Log failed attempt
            LoginAttempt::log($email, 'failed', 'wrong_password', $user->id ?? null);

            // Increment failed login attempts and potentially lock account
            if ($user) {
                $wasLocked = $user->incrementFailedLogins(5); // Lock after 5 failed attempts

                if ($wasLocked) {
                    throw ValidationException::withMessages([
                        $this->username() => ['Your account has been locked due to too many failed login attempts. Please contact support.'],
                    ]);
                }
            }
        }

        return $success;
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

        // Check if user is approved (for roles requiring HEA approval)
        if (in_array($user->requested_role, ['academic_advisor', 'coordinator', 'resource_person'])) {
            if ($user->approval_status === 'pending') {
                auth()->logout();
                return redirect()->route('login')
                    ->with('warning', 'Your registration is pending HEA approval. You will receive an email once your account is approved.');
            }

            if ($user->approval_status === 'rejected') {
                auth()->logout();
                return redirect()->route('login')
                    ->with('error', 'Your registration has been rejected. Please contact the HEA office for more information.');
            }
        }

        // MANDATORY 2FA for all users (except external lecturers)

        // First login: 2FA not set up yet
        if (!$user->two_factor_verified_at) {
            // Redirect to 2FA setup (stay logged in for setup)
            return redirect()->route('2fa.setup')
                ->with('status', 'Please set up two-factor authentication to secure your account.');
        }

        // Subsequent logins: 2FA already set up
        // Store user ID in session BEFORE logout to prevent session loss
        $userId = $user->id;

        // Logout but preserve session data
        auth()->logout();

        // Regenerate session to prevent fixation attacks, but keep our data
        $request->session()->regenerate();

        // Store user ID for 2FA verification
        $request->session()->put('2fa_user_id', $userId);

        // Force session save to ensure data persists before redirect
        $request->session()->save();

        return redirect()->route('2fa.login');
    }
}
