<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Template;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\DeletionService;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Models\Role;
use App\Notifications\AccountCreation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workspace = Workspace::find(session()->get('workspace_id'));
        $clients = $workspace->clients ?? [];
        return view('clients.clients', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create_client');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 300);
        $formFields = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'company' => 'required',
            'email' => ['required', 'email', 'unique:clients,email'],
            'phone' => 'required',
            'country_code' => 'required',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip' => 'required',
            'dob' => 'required',
            'doj' => 'required'
        ]);

        $formFields['password'] = bcrypt($formFields['password']);

        if ($request->hasFile('profile')) {
            $formFields['photo'] = $request->file('profile')->store('photos', 'public');
        } else {
            $formFields['photo'] = 'photos/no-image.jpg';
        }
        $dob = $request->input('dob');
        $doj = $request->input('doj');
        $password = $request->input('password');
        $formFields['dob'] = format_date($dob, false, app('php_date_format'), 'Y-m-d');
        $formFields['doj'] = format_date($doj, false, app('php_date_format'), 'Y-m-d');

        $role_id = Role::where('guard_name', 'client')->first()->id;
        $workspace = Workspace::find(session()->get('workspace_id'));

        $require_ev = isAdminOrHasAllDataAccess() && $request->has('require_ev') && $request->input('require_ev') == 0 ? 0 : 1;
        $status = isAdminOrHasAllDataAccess() && $request->has('status') && $request->input('status') == 1 ? 1 : 0;
        if ($require_ev == 0) {
            $formFields['email_verified_at'] = now()->tz(config('app.timezone'));
        }
        $formFields['status'] = $status;

        $client = Client::create($formFields);

        try {
            if ($require_ev == 1) {
                $client->notify(new VerifyEmail($client));
            }
            $workspace->clients()->attach($client->id);
            $client->assignRole($role_id);
            if (isEmailConfigured()) {
                $account_creation_template = Template::where('type', 'email')
                    ->where('name', 'account_creation')
                    ->first();
                if (!$account_creation_template || ($account_creation_template->status !== 0)) {
                    $client->notify(new AccountCreation($client, $password));
                }
            }
            Session::flash('message', 'Client created successfully.');
            return response()->json(['error' => false, 'id' => $client->id]);
        } catch (TransportExceptionInterface $e) {

            $client = Client::findOrFail($client->id);
            $client->delete();
            return response()->json(['error' => true, 'message' => 'Client couldn\'t be created, please check email settings.']);
        } catch (Throwable $e) {
            // Catch any other throwable, including non-Exception errors

            $client = Client::findOrFail($client->id);
            $client->delete();
            return response()->json(['error' => true, 'message' => 'Client couldn\'t be created, please check email settings.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $workspace = Workspace::find(session()->get('workspace_id'));
        $client = Client::findOrFail($id);
        $projects = $client->projects;
        $tasks = $client->tasks()->count();
        $users = $workspace->users;
        $clients = $workspace->clients;
        return view('clients.client_profile', ['client' => $client, 'projects' => $projects, 'tasks' => $tasks, 'users' => $users, 'clients' => $clients, 'auth_user' => getAuthenticatedUser()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.update_client')->with('client', $client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formFields = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'company' => 'required',
            'phone' => 'required',
            'country_code' => 'required',
            'email' => [
                'required',
                Rule::unique('clients')->ignore($id),
            ],
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'zip' => 'required',
            'dob' => 'required',
            'doj' => 'required',
        ]);
        $client = Client::findOrFail($id);
        if ($request->hasFile('upload')) {
            if ($client->photo != 'photos/no-image.jpg' && $client->photo !== null)
                Storage::disk('public')->delete($client->photo);
            $formFields['photo'] = $request->file('upload')->store('photos', 'public');
        }
        $dob = $request->input('dob');
        $doj = $request->input('doj');
        $formFields['dob'] = format_date($dob, false, app('php_date_format'), 'Y-m-d');
        $formFields['doj'] = format_date($doj, false, app('php_date_format'), 'Y-m-d');

        $status = isAdminOrHasAllDataAccess() && $request->has('status') && $request->input('status') == 1 ? 1 : $client->status;
        $formFields['status'] = $status;

        $client->update($formFields);

        Session::flash('message', 'Client details updated successfully.');
        return response()->json(['error' => false, 'id' => $client->id]);
    }

    public function get($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['client' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $response = DeletionService::delete(Client::class, $id, 'Client');
        $client->todos()->delete();
        return $response;
    }


    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:clients,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedClients = [];
        $deletedClientNames = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $client = Client::findOrFail($id);
            if ($client) {
                $deletedClients[] = $id;
                $deletedClientNames[] = $client->first_name . ' ' . $client->last_name;
                DeletionService::delete(Client::class, $id, 'Client');
                $client->todos()->delete();
            }
        }
        return response()->json(['error' => false, 'message' => 'Clients(s) deleted successfully.', 'id' => $deletedClients, 'titles' => $deletedClientNames]);
    }



    public function list()
    {
        $workspace = Workspace::find(session()->get('workspace_id'));
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $type = request('type');
        $typeId = request('typeId');
        if ($type && $typeId) {
            if ($type == 'project') {
                $project = Project::find($typeId);
                $clients = $project->clients();
            } elseif ($type == 'task') {
                $task = Task::find($typeId);
                $clients = $task->project->clients();
            } else {
                $clients = $workspace->clients();
            }
        } else {
            $clients = $workspace->clients();
        }
        $clients = $clients->when($search, function ($query) use ($search) {
            return $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('company', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        });
        $totalclients = $clients->count();

        $clients = $clients->orderBy($sort, $order)
            ->paginate(request("limit"))

            // ->withQueryString()
            ->through(fn ($client) => [
                'id' => $client->id,
                'first_name' => $client->first_name,
                'last_name' => $client->last_name,
                'company' => $client->company,
                'email' => $client->email,
                'phone' => $client->phone,
                'profile' => "<div class='avatar avatar-md pull-up' title='" . $client->first_name . " " . $client->last_name . "'>
                                <a href='/clients/profile/" . $client->id . "'>
                                <img src='" . ($client->photo ? asset('storage/' . $client->photo) : asset('storage/photos/no-image.jpg')) . "' alt='Avatar' class='rounded-circle'>
                                </a>
                                </div>",
                'projects' => isAdminOrHasAllDataAccess('client', $client->id) ? count($workspace->projects) : count($client->projects),
                'status' => $client->status,
                'created_at' => format_date($client->created_at, true),
                'updated_at' => format_date($client->updated_at, true),
                'tasks' => isAdminOrHasAllDataAccess('client', $client->id) ? count($workspace->tasks) : $client->tasks()->count(),
            ]);

        return response()->json([
            "rows" => $clients->items(),
            "total" => $totalclients,
        ]);
    }

    public function verify_email(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/home')->with('message', 'Email verified successfully.');
    }
}
