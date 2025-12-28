<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Require2FASetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip for external lecturers (they don't use 2FA)
        if ($user && $user->current_role === 'external_lecturer') {
            return $next($request);
        }

        // If user is authenticated but hasn't completed 2FA setup
        if ($user && !$user->two_factor_verified_at) {
            // Allow access to 2FA setup, verification routes, and email verification page
            $allowedRoutes = ['2fa.setup', '2fa.verify', 'verification.notice', 'verification.resend', 'verification.verify', 'logout'];
            if (!$request->routeIs($allowedRoutes)) {
                return redirect()->route('2fa.setup')
                    ->with('warning', 'Please complete two-factor authentication setup before continuing.');
            }
        }

        return $next($request);
    }
}
