<?php

// app/Http/Middleware/CheckAdminOrLeaveEditor.php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\LeaveEditor;

class CheckAdminOrLeaveEditor
{
    public function handle($request, Closure $next)
    {
        $user = getAuthenticatedUser();

        // Check if the user is an admin or a leave editor based on their presence in the leave_editors table
        if ($user->hasRole('admin') || LeaveEditor::where('user_id', $user->id)->exists()) {
            return $next($request);
        }
        if (!$request->ajax()) {
            return redirect('/home')->with('error', get_label('not_authorized', 'You are not authorized to perform this action.'));
        }
        return response()->json(['error' => true, 'message' => get_label('not_authorized', 'You are not authorized to perform this action.')]);
    }
}
