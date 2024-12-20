<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class StoreMaterialController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            // Add more validation rules as needed
        ]);

        // Create a new material using the validated data
        $material = Material::create($validatedData);

        // Optionally, you can redirect the user to a success page or perform other actions

        return redirect()->back()->with('success', 'Material created successfully.');
    }
}