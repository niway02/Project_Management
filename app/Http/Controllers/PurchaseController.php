<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase; // Assuming you have a Purchase model
use App\Models\Equipment; // Assuming you have an Equipment model
use App\Models\Supplier; // Assuming you have a Supplier model

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Create the purchase record
        $purchase = Purchase::create([
            'equipment_id' => $request->equipment_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'total' => $request->quantity * $request->price,
            'date' => now(),
        ]);

        // Update the equipment's stock
        $equipment = Equipment::find($request->equipment_id);
        $equipment->quantity += $request->quantity;
        $equipment->save();

        return response()->json(['message' => 'Purchase successful', 'purchase' => $purchase], 201);
    }
}