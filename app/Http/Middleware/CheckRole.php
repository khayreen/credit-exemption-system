<?php

namespace App\Http\Middleware;

use App\Models\AccessLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * This function acts as a guard for your routes.
     *
     * @param  \Illuminate\Http\Request  $request The incoming web request.
     * @param  \Closure  $next The next action in the middleware chain.
     * @param  string  ...$roles A list of roles that are allowed to access the route (e.g., 'student', 'academic_advisor').
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // First, check if a user is logged in at all. If not, send to login page.
        if (!Auth::check()) {
            return redirect('login');
        }

        // Get the currently authenticated user.
        $user = Auth::user();

        // Loop through the roles required by the route (e.g., ['student']).
        foreach ($roles as $role) {
            // If the user's role matches the required role, let them pass.
            if ($user->current_role == $role) {
                return $next($request);
            }
        }

        // Log the unauthorized access attempt before aborting
        AccessLog::log(
            $request->path(),
            $request->method(),
            'denied',
            'User role (' . ($user->current_role ?? 'none') . ') not in allowed roles: ' . implode(', ', $roles),
            $user->id
        );

        // If the loop finishes and no role matched, the user is not authorized.
        // Show a "403 Forbidden" error page.
        abort(403, 'Unauthorized Action');
    }
}
