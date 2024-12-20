<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $roles = Role::all();
        return view('users.account', ['user' => getAuthenticatedUser(), 'roles' => $roles]);
    }

    public function update(Request $request, $id)
    {

        $rules = [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => 'required',
            'role' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip' => 'required',
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|required_with:password|same:password',
        ];

        if (isAdminOrHasAllDataAccess()) {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ];
        }

        $formFields = $request->validate($rules);
        if (isset($formFields['password']) && !empty($formFields['password'])) {
            $formFields['password'] = bcrypt($formFields['password']);
        } else {
            unset($formFields['password']);
        }


        $user = isUser() ? User::findOrFail($id) : Client::findOrFail($id);
        $user->update($formFields);
        $user->syncRoles($request->input('role'));

        Session::flash('message', 'Profile details updated successfully.');
        return response()->json(['error' => false]);
    }

    public function update_photo(Request $request, $id)
    {
        if ($request->hasFile('upload')) {
            $user = isUser() ? User::findOrFail($id) : Client::findOrFail($id);
            if ($user->photo != 'photos/no-image.jpg' && $user->photo !== null)
                Storage::disk('public')->delete($user->photo);
            $formFields['photo'] = $request->file('upload')->store('photos', 'public');
            $user->update($formFields);

            Session::flash('message', 'Profile picture updated successfully.');
            return response()->json(['error' => false]);
        } else {
            return response()->json(['error' => true, 'message' => 'No profile picture selected!']);
        }
    }

    public function destroy($id)
    {
        $user = isUser() ? User::findOrFail($id) : Client::findOrFail($id);
        isUser() ? DeletionService::delete(User::class, $id, 'Account') : DeletionService::delete(Client::class, $id, 'Account');
        $user->todos()->delete();
        return redirect('/');
    }
}
