<?php

namespace App\Http\Middleware;

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

        // Check if user's email matches the admin email
        $adminEmail = config('app.admin_email');

        if (auth()->user()->email !== $adminEmail) {
            abort(403, 'Unauthorized. This page is only accessible by system administrators.');
        }

        return $next($request);
    }
}
