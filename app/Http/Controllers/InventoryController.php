<?php
namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Equipment;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function dashboard()
    {
        $totalMaterials = Material::sum('quantity');
        $totalEquipments = Equipment::sum('quantity');

        $lowInquantityMaterials = Material::where('quantity', '<', 'min_quantity')->count();
        $lowInquantityEquipments = Equipment::where('quantity', '<', 'min_quantity')->count();

        $materials = Material::select('item', 'quantity', 'min_quantity')->get();
        $equipments = Equipment::select('item', 'quantity', 'min_quantity')->get();

        $inventoryData = $this->getInventoryData($materials, $equipments);

        $inventorySummary = [
            'totalMaterials' => $totalMaterials,
            'totalEquipments' => $totalEquipments,
            'lowInquantityMaterials' => $lowInquantityMaterials,
            'lowInquantityEquipments' => $lowInquantityEquipments
        ];

        return view('inventory.dashboard', compact('inventoryData', 'inventorySummary'));
    }

    private function getInventoryData($materials, $equipments)
    {
        $inventoryData = [];

        foreach ($materials as $material) {
            $inventoryData[] = [
                'item' => $material->item,
                'category' => 'Material',
                'totalQuantity' => $material->quantity,
                'lowQuantity' => $material->quantity < $material->min_quantity ? $material->quantity : 0,
                'action' => ''
            ];
        }

        foreach ($equipments as $equipment) {
            $inventoryData[] = [
                'item' => $equipment->item,
                'category' => 'Equipment',
                'totalQuantity' => $equipment->quantity,
                'lowQuantity' => $equipment->quantity < $equipment->min_quantity ? $equipment->quantity : 0,
                'action' => ''
            ];
        }

        return $inventoryData;
    }
}