<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use Illuminate\Support\Facades\Session;

class PriorityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('priority.list');
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
            'color' => ['required']
        ]);
        $slug = generateUniqueSlug($request->title, Priority::class);
        $formFields['slug'] = $slug;

        if ($priority = Priority::create($formFields)) {
            return response()->json(['error' => false, 'message' => 'Priority created successfully.', 'id' => $priority->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Priority couldn\'t created.']);
        }
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $priority = Priority::orderBy($sort, $order);

        if ($search) {
            $priority = $priority->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $priority->count();
        $priority = $priority
            ->paginate(request("limit"))
            ->through(
                fn ($priority) => [
                    'id' => $priority->id,
                    'title' => $priority->title,
                    'color' => '<span class="badge bg-' . $priority->color . '">' . $priority->title . '</span>',
                    'created_at' => format_date($priority->created_at, true),
                    'updated_at' => format_date($priority->updated_at, true),
                ]
            );


        return response()->json([
            "rows" => $priority->items(),
            "total" => $total,
        ]);
    }

    public function get($id)
    {
        $priority = Priority::findOrFail($id);
        return response()->json(['priority' => $priority]);
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
            'color' => ['required']
        ]);
        $slug = generateUniqueSlug($request->title, Priority::class, $request->id);
        $formFields['slug'] = $slug;
        $priority = Priority::findOrFail($request->id);

        if ($priority->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Priority updated successfully.', 'id' => $priority->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Priority couldn\'t updated.']);
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
        $priority = Priority::findOrFail($id);
        $priority->projects(false)->update(['priority_id' => null]);
        $priority->tasks(false)->update(['priority_id' => null]);
        $response = DeletionService::delete(Priority::class, $id, 'Priority');
        return $response;
    }

    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:priorities,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $priority = Priority::findOrFail($id);
            $priority->projects(false)->update(['priority_id' => null]);
            $priority->tasks(false)->update(['priority_id' => null]);
            $deletedIds[] = $id;
            $deletedTitles[] = $priority->title;
            DeletionService::delete(Priority::class, $id, 'Status');
        }

        return response()->json(['error' => false, 'message' => 'Priority/Priorities deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles]);
    }
}
