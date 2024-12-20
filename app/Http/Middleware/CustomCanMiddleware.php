<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Access\AuthorizationException;

class CustomCanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $guards = ['web', 'client']; // Add more guards as needed

        foreach ($guards as $guard) {
            foreach ($permissions as $permission) {
                if (auth($guard)->check() && auth($guard)->user()->can($permission)) {
                    return $next($request);
                }
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            // if (!$request->has('flash_message_only')) {
            //     Session::flash('error', get_label('not_authorized', 'You are not authorized to perform this action.'));
            // }
            return response()->json(['error' => true, 'message' => get_label('not_authorized', 'You are not authorized to perform this action.')]);
        } else {
            // For regular web requests, return the view.
            return response()->view('auth.not-authorized', [], 403);
        }
    }
}
