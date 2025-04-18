<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.roles.index',['roles'=>Role::all()]);
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Role::create([
            'name' =>$request->name,
            'salary' =>(double) $request->salary,
            'incentives' =>(double) $request->incentives ?? 0,
        ]);
        return redirect()->back()->with('success','role added successfully');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role=Role::findOrFail($id);
        return view('admin.roles.edit',compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role=Role::findOrFail($id);
        $role->update([
            'name' =>$request->name,
            'salary' =>(double) $request->salary,
            'incentives' =>(double) $request->incentives ?? 0,
        ]);
        return redirect()->back()->with('success','role updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role=Role::findOrFail($id);
        $role->delete();
        return view('admin.roles.index',['roles'=>Role::all()]);
        
    }
}
