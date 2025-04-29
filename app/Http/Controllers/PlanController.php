<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use Illuminate\Http\Request;
use App\Models\Plan;
use Carbon\Carbon;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlanRequest $request)
    {
        $period = 0;
        switch ($request->period) {
            case 'month':
                $period = 30;
                break;
            case 'year':
                $period = 365;
                break;

            default:
                $period = 30;
                break;
        }
        Plan::create([
            'title' => $request->title,
            'period' => $period,
            'icon' => $request->icon,
            'price' => $request->price,
            'features' => $request->features,
        ]);
        return redirect()->back()->with('success', 'Plan created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.show', compact('plan'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(PlanRequest $request, string $id)
    {
        $plan = Plan::findOrFail($id);
        $period = 0;
        switch ($request->period) {
            case 'month':
                $period = 30;
                break;
            case 'year':
                $period = 365;
                break;

            default:
                $period = 30;
                break;
        }
        $plan->update([
            'title' => $request->title,
            'period' => $period,
            'icon' => $request->icon,
            'price' => $request->price,
            'features' => $request->features,
        ]);
        return redirect()->back()->with('success', 'Plan Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plan =Plan::findOrFail($id);
        $plan->delete();
        return to_route('plans.index')->with('success',"plan $id deleted succesfully!");
    }
    public function subscribers($id) {
        $plan=Plan::findOrFail($id);
        return view('admin.plans.subscribers',compact('plan'));
    }
}
