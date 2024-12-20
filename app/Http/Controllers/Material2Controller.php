<?php

namespace App\Http\Controllers;

use App\Models\Material2;
use Illuminate\Http\Request;

class Material2Controller extends Controller
{
    public function index(){
        $materials=Material2::all();
        return view('material2.index',['materials'=>$materials]);
    }

    public function store(Request $req){
    $material=$req->validate([
        'item' => 'required',
        'quantity' => 'required',
        'rate_with_vat' => 'required',
        'amount'=>'required',
        'remark' => 'required',
        'status'=>'required',
        'warehouse2_id'=>'required',
        'material_type'=>'required',
        'reorder_quantity'=>'required',
        'min_quantity'=>'required',

    ]);
        Material2::create($material);
        return redirect()->route('material2.index'); 
    }
    public function update(Request $request){
       $material=Material2::find($request->id);
    //    dump($request->all());
    //    dd($material);
        $validatedData = $request->validate([
            'item' => 'required',
            'quantity' => 'required',
            'rate_with_vat' => 'required',
            'amount'=>'required',
            'remark' => 'required',
            'status'=>'required',
            'warehouse2_id'=>'required',
            'material_type'=>'required',
            'reorder_quantity'=>'required',
            'min_quantity'=>'required',
        ]);
        if ($request->amount < $request->min_quantity) {
            return back()->withErrors(['amount' => "The amount can't be less than the minimum allowed quantity."])->withInput();
        }
        
        $material->update($validatedData);
        return redirect()->route('material2.index')->with('success', 'Material updated successfully.');
    }
    public function show($id){
 $material=Material2::findOrFail($id);
 return view('material2.show',['material'=>$material]);
    }

    public function destroy($id){
        $material=Material2::findOrFail($id);
        $material->delete();
      return route("material2.index");  
      }
}
