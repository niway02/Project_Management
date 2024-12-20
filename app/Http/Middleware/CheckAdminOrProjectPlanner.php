<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrProjectPlanner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = getAuthenticatedUser();

        // Check if the user is an admin or a leave editor based on their presence in the leave_editors table
       //if ($user->hasRole('admin') || LeaveEditor::where('user_id', $user->id)->exists()) {
        if (!$user || (!$user->hasRole('admin')&& !$user->hasRole('Project Planner'))) {
            return $next($request);
        }
        if (!$request->ajax()) {
            return redirect('/home')->with('error', get_label('not_authorized', 'You are not authorized to perform this action.'));
        }
        return response()->json(['error' => true, 'message' => get_label('not_authorized', 'You are not authorized to perform this action.')]);
    }
}
