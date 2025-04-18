<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use Illuminate\Support\Facades\Storage;

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
            "description"=>"nullable|max:255",
            "photo"=>"nullable"
        ]);

        $clinic =Clinic::findOrFail($id);

        $clinic->update([
            "name"=>$request->name,
            "place"=>$request->floor,
            "description"=>$request->description,
        ]);
        if ($request->hasFile('photo')) {

            $photo =  Storage::disk('clinics')->put('/', $request->file('photo'));
            $clinic->photo = $photo;
            $clinic->save();
        }
        
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
