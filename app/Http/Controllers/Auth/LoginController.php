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
     * We use it to redirect them to our 2FA verification flow.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Log the user out of the main session guard.
        auth()->logout();
        // Store their ID in the session so we know who is trying to 2FA.
        $request->session()->put('2fa_user_id', $user->id);
        // Redirect to the 2FA page.
        return redirect()->route('2fa.index');
    }
}
