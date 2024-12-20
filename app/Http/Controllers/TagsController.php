<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\DeletionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class TagsController extends Controller
{
    public function index()
    {
        return view('tags.list');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required'],
            'color' => ['required']
        ]);
        $slug = generateUniqueSlug($request->title, Tag::class);
        $formFields['slug'] = $slug;
        $tag = Tag::create($formFields);

        Session::flash('message', 'Tag created successfully.');
        return response()->json(['error' => false, 'message' => 'Tag created successfully.', 'id' => $tag->id]);
    }

    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $tags = Tag::orderBy($sort, $order); // or 'desc'

        if ($search) {
            $tags = $tags->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $tags->count();
        $tags = $tags
            ->paginate(request("limit"))
            ->through(
                fn ($tag) => [
                    'id' => $tag->id,
                    'title' => $tag->title,
                    'color' => '<span class="badge bg-' . $tag->color . '">' . $tag->title . '</span>',
                    'created_at' => format_date($tag->created_at, true),
                    'updated_at' => format_date($tag->updated_at, true),
                ]
            );


        return response()->json([
            "rows" => $tags->items(),
            "total" => $total,
        ]);
    }

    public function get($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json(['tag' => $tag]);
    }

    public function update(Request $request)
    {
        $formFields = $request->validate([
            'id' => ['required'],
            'title' => ['required'],
            'color' => ['required']
        ]);
        $slug = generateUniqueSlug($request->title, Tag::class, $request->id);
        $formFields['slug'] = $slug;

        $tag = Tag::findOrFail($request->id);

        if ($tag->update($formFields)) {
            return response()->json(['error' => false, 'message' => 'Tag updated successfully.', 'id' => $tag->id]);
        } else {
            return response()->json(['error' => true, 'message' => 'Tag couldn\'t updated.']);
        }
    }

    public function get_suggestions()
    {
        $tags = Tag::pluck('title');
        return response()->json($tags);
    }

    public function get_ids(Request $request)
    {
        $tagNames = $request->input('tag_names');
        $tagIds = Tag::whereIn('title', $tagNames)->pluck('id')->toArray();
        return response()->json(['tag_ids' => $tagIds]);
    }

    public function destroy($id)
    {
        $response = DeletionService::delete(Tag::class, $id, 'Tag');
        return $response;
    }
    public function destroy_multiple(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'ids' => 'required|array', // Ensure 'ids' is present and an array
            'ids.*' => 'integer|exists:tags,id' // Ensure each ID in 'ids' is an integer and exists in the table
        ]);

        $ids = $validatedData['ids'];
        $deletedIds = [];
        $deletedTitles = [];
        // Perform deletion using validated IDs
        foreach ($ids as $id) {
            $tag = Tag::findOrFail($id);
            $deletedIds[] = $id;
            $deletedTitles[] = $tag->title;
            DeletionService::delete(Tag::class, $id, 'Tag');
        }

        return response()->json(['error' => false, 'message' => 'Tag(s) deleted successfully.', 'id' => $deletedIds, 'titles' => $deletedTitles]);
    }
}
