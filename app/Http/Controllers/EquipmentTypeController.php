<?php
// app/Http/Controllers/EquipmentTypeController.php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $equipmentTypes = EquipmentType::all();
        return view('equipment_types.index', compact('equipmentTypes'));
    }
    public function list()
    {
        $search = request('search');
        $sort = (request('sort')) ? request('sort') : "id";
        $order = (request('order')) ? request('order') : "DESC";
        $status = EquipmentType::orderBy($sort, $order);

        if ($search) {
            $status = $status->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        $total = $status->count();
        $status = $status
            ->paginate(request("limit"))
            ->through(
                fn ($status) => [
                    'id' => $status->id,
                    'title' => $status->title,
                    'color' => '<span class="badge bg-' . $status->color . '">' . $status->title . '</span>',
                    'created_at' => format_date($status->created_at, true),
                    'updated_at' => format_date($status->updated_at, true),
                ]
            );


        return response()->json([
            "rows" => $status->items(),
            "total" => $total,
        ]);
    }
    public function create()
    {
        return view('equipment_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:equipment_types|max:255',
            'description' => 'nullable|string',
        ]);

        EquipmentType::create($request->all());
        return redirect()->route('equipment_types.index')->with('success', 'Equipment Type created successfully.');
    }

    public function edit(EquipmentType $equipmentType)
    {
        return view('equipment_types.edit', compact('equipmentType'));
    }

    public function update(Request $request, EquipmentType $equipmentType)
    {
        $request->validate([
            'name' => 'required|max:255|unique:equipment_types,name,' . $equipmentType->id,
            'description' => 'nullable|string',
        ]);

        $equipmentType->update($request->all());
        return redirect()->route('equipment_types.index')->with('success', 'Equipment Type updated successfully.');
    }

    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();
        return redirect()->route('equipment_types.index')->with('success', 'Equipment Type deleted successfully.');
    }
}