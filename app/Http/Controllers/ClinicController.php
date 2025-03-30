<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\TimeTable;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('clinics.index',['clinics'=>Clinic::all()]);
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required|min:2",
            "floor"=>"required|min:1",
        ]);

        Clinic::create([
            "name"=>$request->name,
            "place"=>$request->floor
        ]);
        return redirect()->back()->with('success','clinic added successfully');

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
        
        $clinic= Clinic::findOrFail($id);
        return view('clinics.edit',['clinic'=>$clinic]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name"=>"required|min:2",
            "floor"=>"required|min:1",
        ]);

        $clinic =Clinic::findOrFail($id);

        $clinic->update([
            "name"=>$request->name,
            "place"=>$request->floor
        ]);
        
        return redirect()->back()->with('success','clinic updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clinic= Clinic::findOrFail($id);
        $clinic->delete();
        return redirect()->back()->with('success','clinic deleted successfully');
    }
  

   
}
