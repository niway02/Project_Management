<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HasWorkspace
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

        if (session()->get('workspace_id') == 0) {
            if (!$request->ajax()) {
                return redirect('/home')->with('error', get_label('must_workspace_participant', 'You must be participant in atleast one workspace'));
            }
            Session::flash('error', get_label('must_workspace_participant', 'You must be participant in atleast one workspace'));
            return response()->json(['error' => true, 'message' => get_label('must_workspace_participant', 'You must be participant in atleast one workspace')]);
        }
        return $next($request);
    }
}
