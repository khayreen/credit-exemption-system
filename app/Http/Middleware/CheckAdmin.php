<?php

namespace App\Http\Middleware;

use App\Models\AccessLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $user = auth()->user();

        // Check if user has admin role
        if ($user->current_role !== 'admin') {
            // Log unauthorized access attempt
            AccessLog::log(
                $request->path(),
                $request->method(),
                'denied',
                'User does not have admin role',
                $user->id
            );

            abort(403, 'Unauthorized. This page is only accessible by system administrators.');
        }

        // Check if account is locked
        if ($user->isLocked()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been locked. Please contact support.');
        }

        return $next($request);
    }
}
