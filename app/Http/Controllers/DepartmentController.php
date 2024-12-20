<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = isset($request->status) && $request->status !== '' ? $request->status : "";
        $selectedTags = $request->input('tags', []);
        $where = [];
    
        if ($status != '') {
            $where['status_id'] = $status;
        }
    
        $is_favorite = 0;
        if ($request->type === 'favorite') {
            $where['is_favorite'] = 1;
            $is_favorite = 1;
        }
    
        $sort = $request->input('sort', 'id');
        $order = 'desc';
        if ($sort == 'newest') {
            $sort = 'created_at';
            $order = 'desc';
        } elseif ($sort == 'oldest') {
            $sort = 'created_at';
            $order = 'asc';
        } elseif ($sort == 'recently-updated') {
            $sort = 'updated_at';
            $order = 'desc';
        } elseif ($sort == 'earliest-updated') {
            $sort = 'updated_at';
            $order = 'asc';
        }
    
        $departments = Department::query()
            ->where($where);
    
        if (!empty($selectedTags)) {
            $departments = $departments->whereHas('tags', function ($q) use ($selectedTags) {
                $q->whereIn('tags.id', $selectedTags);
            });
        }
    
        $departments = $departments->orderBy($sort, $order)
            ->paginate(6);
    
     return view('departments.index', compact('departments', 'is_favorite', 'selectedTags', 'status'));
      //return response()->json($departments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       // return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        dd("hello");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
