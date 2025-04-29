<?php

namespace App\Http\Controllers;

use App\Models\Eventt;
use Illuminate\Http\Request;

class EventtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.events.index',['events'=>Eventt::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'min:2|max:255|required',
            'description'=>'min:2|required',
            'date'=>'required|date',
        ]);

        Eventt::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'date'=>$request->date,
        ]);

        return redirect()->back()->with('success','event created successfully!');

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        return view('admin.events.edit',['event'=>Eventt::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event =Eventt::findOrFail($id);
        $request->validate([
            'title'=>'min:2|max:255|required',
            'description'=>'min:2|required',
            'date'=>'required|date',
        ]);
        $event->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'date'=>$request->date,
        ]);

        return redirect()->back()->with('success','event updated successfully!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event =Eventt::findOrFail($id);
        $event->delete();
        return to_route('events.index')->with('success','Event deleted successfully!');
    }
}
