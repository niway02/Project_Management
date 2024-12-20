<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workspace;  
use App\Models\EquipmentInventory;
class EquipmentInventoriesController extends Controller
{
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

        $equipmentInventories = EquipmentInventory::all();
        return view('equipment-inventories.index', compact('equipmentInventories'));
    }
}
