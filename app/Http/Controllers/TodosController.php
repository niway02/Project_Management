<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use App\Models\Client;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TodosController extends Controller
{
    protected $workspace;
    protected $user;
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            // fetch session and use it in entire class with constructor
            $this->workspace = Workspace::find(session()->get('workspace_id'));
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = $this->user->todos()
            ->orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('todos.list', ['todos' => $todos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('todos.create_todo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'priority' => ['required'],
            'description' => ['nullable']
        ]);
        $formFields['workspace_id'] = $this->workspace->id;

        $todo = new Todo($formFields);


        $todo->creator()->associate($this->user);

        $todo->save();

        Session::flash('message', 'Todo created successfully.');
        return response()->json(['error' => false, 'id' => $todo->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $todo = Todo::findOrFail($id);
        return view('todos.edit_todo', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'priority' => ['required'],
            'description' => ['nullable']
        ]);
        $todo = Todo::findOrFail($request->id);
        // $this->authorize('update', [$this->user, $todo]);
        if ($todo->update($formFields)) {
            Session::flash('message', 'Todo updated successfully.');
            return response()->json(['error' => false, 'id' => $request->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Todo couldn\'t updated.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

        $response = DeletionService::delete(Todo::class, $id, 'Todo');
        return $response;
    }



    public function update_status(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'status' => ['required']

        ]);
        $id = $request->id;
        $status = $request->status;
        $todo = Todo::findOrFail($id);
        $todo->is_completed = $status;
        $statusText = $status == 1 ? 'Completed' : 'Pending';
        if ($todo->save()) {
            return response()->json(['error' => false, 'message' => 'Status updated successfully.', 'id' => $id, 'activity_message' => $this->user->first_name . ' ' . $this->user->last_name . ' marked todo ' . $todo->title . ' as ' . $statusText]);
        } else {
            return response()->json(['error' => true, 'message' => 'Status couldn\'t updated.']);
        }
    }

    public function get($id)
    {
        $todo = Todo::findOrFail($id);
        return response()->json(['todo' => $todo]);
    }
}
