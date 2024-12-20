<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $typeModel, $typeKey, $parameterName, $redirect = null)
    {
        $user = getAuthenticatedUser();
        // Extract the parameter from the route
        $itemId = $request->route($parameterName);
        // Check if the user has the 'admin' role or if they have access with the given ID
        if (isAdminOrHasAllDataAccess() || ($this->hasAccess($user, $typeKey, $typeModel, $itemId))) {
            return $next($request); // User is authorized, proceed with the request
        }

        if (!$request->ajax()) {
            return redirect('/' . $redirect)->with('error', get_label('un_authorized_action', 'Un authorized action.'));
        }
        Session::flash('error', get_label('un_authorized_action', 'Un authorized action.'));
        return response()->json(['error' => true, 'message' => get_label('un_authorized_action', 'Un authorized action.')]);
    }

    private function hasAccess($user, $typeKey, $typeModel, $itemId)
    {
        // Check if $user->$typeKey is a relationship or a collection
        if ($user->$typeKey() instanceof Illuminate\Database\Eloquent\Relations\Relation) {
            return $user->$typeKey->contains($typeModel::find($itemId));
        } else {
            if($typeKey=='tasks' || $typeKey=='payslips' || $typeKey=='projects'){
                return $user->$typeKey()->get()->contains($typeModel::find($itemId));
            }else{
                return $user->$typeKey()->contains($typeModel::find($itemId));
            }
        }
    }
}
