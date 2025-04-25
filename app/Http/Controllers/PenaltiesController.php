<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Penalty;
use Illuminate\Http\Request;

class PenaltiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($role,$id)
    {
        if ($role=='employee') {
            $emp =Employee::findOrFail($id);
            return view('admin.penalties.create',compact('emp','role'));
          
        } else if ($role=='doctor') {
            $emp =Doctor::findOrFail($id);
            return view('admin.penalties.create',compact('emp','role'));
           
        } else {
            abort(403);
        }
        
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$role,$id)
    {
        if ($role=='employee') {
            $emp =Employee::findOrFail($id);
            if ($emp->penalty) {
               $emp->penalty()->update([
                'month'=>date_create()->format("M-Y"),
                'days'=>(int) $request->days,
                'description'=>$request->description
               ]);
            } else {
                $penalty=new Penalty();
                $month=date_create()->format("M-Y");
                $penalty->employee_id=$emp->id;
                $penalty->days=(int) $request->days;
                $penalty->description=$request->description;
                $penalty->month =$month;
                $penalty->save();
            }
            
            
                return redirect()->back()->with('success','penalty created successfully');
          
        } else if ($role=='doctor') {
            $emp =Doctor::findOrFail($id);
            if ($emp->penalty) {
                $emp->penalty()->update([
                 'month'=>date_create()->format("M-Y"),
                 'days'=>(int) $request->days,
                 'description'=>$request->description
                ]);
             } else {
                 $penalty=new Penalty();
                 $month=date_create()->format("M-Y");
                 $penalty->doctor_id=$emp->id;
                 $penalty->days=(int) $request->days;
                 $penalty->description=$request->description;
                 $penalty->month =$month;
                 $penalty->save();
             }
                return redirect()->back()->with('success','penalty created successfully');
           
        } else {
            abort(403);
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
