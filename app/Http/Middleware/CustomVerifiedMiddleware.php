<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the 'web' guard (for users) email is verified
        if (Auth::guard('web')->check() && !Auth::guard('web')->user()->hasVerifiedEmail() && !(getAuthenticatedUser()->hasRole('admin'))) {
            return redirect()->route('verification.notice');
        }

        // Check if the 'client' guard (for clients) email is verified
        if (Auth::guard('client')->check() && !Auth::guard('client')->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice'); // Customize this route
        }
        return $next($request);
    }
}
