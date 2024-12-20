<?php

// app/Http/Middleware/CheckAdminOrLeaveEditor.php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\LeaveEditor;

class CheckAdminOrUser
{
    public function handle($request, Closure $next)
    {        
        // Check if the user is an admin or a leave editor based on their presence in the leave_editors table
        if (isUser()) {
            return $next($request);
        }
        if (!$request->ajax()) {
            return redirect('/home')->with('error', get_label('not_authorized', 'You are not authorized to perform this action.'));
        }
        return response()->json(['error' => true, 'message' => get_label('not_authorized', 'You are not authorized to perform this action.')]);
    }
}
