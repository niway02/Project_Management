<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Services\DeletionService;

class NotesController extends Controller
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
    public function index()
    {
        $notes = $this->user->notes();
        return view('notes.list', ['notes' => $notes]);
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'color' => ['required'],
            'description' => ['nullable']
        ]);
        $formFields['workspace_id'] = $this->workspace->id;
        $formFields['creator_id'] = isClient() ? 'c_' . $this->user->id : 'u_' . $this->user->id;

        if ($note = Note::create($formFields)) {
            Session::flash('message', 'Note created successfully.');
            return response()->json(['error' => false, 'id' => $note->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Note couldn\'t created.']);
        }
    }

    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'color' => ['required'],
            'description' => ['nullable']
        ]);
        $note = Note::findOrFail($request->id);

        if ($note->update($formFields)) {
            Session::flash('message', 'Note updated successfully.');
            return response()->json(['error' => false, 'id' => $note->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Note couldn\'t updated.']);
        }
    }

    public function get($id)
    {
        $note = Note::findOrFail($id);
        return response()->json(['note' => $note]);
    }



    public function destroy($id)
    {
        $response = DeletionService::delete(Note::class, $id, 'Note');
        return $response;
    }
}
