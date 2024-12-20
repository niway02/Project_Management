<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Access\AuthorizationException;

class DemoRestriction
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
        if (config('constants.ALLOW_MODIFICATION') === 0) {
            if ($request->ajax() || $request->wantsJson()) {
                if ($request->has('flash_message')) {
                    Session::flash('error', get_label('demo_restriction', 'This operation is not allowed in demo mode.'));
                }
                return response()->json(['error' => true, 'message' => get_label('demo_restriction', 'This operation is not allowed in demo mode.')]);
            }
            return redirect()->back()->with('error', get_label('demo_restriction', 'This operation is not allowed in demo mode.'));
        }

        // If ALLOW_MODIFICATION is 1, pass the request to the next middleware or controller.
        return $next($request);
    }
}
