<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Employee;
use App\Models\Penalty;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employees.index', ['employees' => Employee::all()->reverse()->load('user')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            $roles=Role::all();
        return view('employees.create',compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'email' => ['required', 'email', 'unique:users'],
                'role' => ['required', Rule::in(DB::table('roles')->pluck('name'))],
                'password' => ['required', 'min:6', 'confirmed'],
                'national_id' => ['required', 'min:5'],
                'phone' => ['required'],
                'home_phone' => ['required'],
                'gender' => ['required', Rule::in(['male', 'female'])],
                'profile' => ['image', 'max:2048', 'nullable'],
            ]
        );
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }


        // user 
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->role == 'super_admin' || $request->role == 'admin') {
            Gate::authorize('isSuperAdmin');
        }
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();
        // Emp
        $emp = new Employee();
        $emp->user_id = $user->id;
        $emp->phone = $request->phone;
        $emp->home_phone = $request->home_phone;
        $emp->gender = $request->gender;
        $emp->role = $request->role;
        $emp->birthDate = $request->birthdate;
        $emp->salary = Role::where('name',$request->role)->first()->salary;
        $emp->national_id = $request->national_id;
        $emp->address = $request->address;
        $emp->save();

        if ($request->hasFile('profile')) {

            $photo =  Storage::disk('employees')->put('/', $request->file('profile'));
            $emp->profile = $photo;
            $emp->save();
        }

        return redirect()->back()->with('success', 'Employee registerd successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $emp = Employee::findOrFail($id)->load('user');
        return view('employees.show', ['emp' => $emp]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

       $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'email' => ['required', 'email'],
                'role' => ['required', Rule::in(DB::table('roles')->pluck('name'))],
                'password' => ['nullable', 'min:6', 'confirmed'],
                'national_id' => ['required', 'min:5'],
                'phone' => ['required'],
                'home_phone' => ['required'],
                'gender' => ['required', Rule::in(['male', 'female'])],
                'profile' => ['image', 'max:2048', 'nullable'],
            ]
        );
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $emp = Employee::findOrFail($id);
        // user 
        $user = User::findOrfail($emp->user_id);
        $user->name = $request->name;
        if ($user->email != $request->email) {
            $email = User::where('email', $request->email)->exists();
            if ($email) {
                return back()->withErrors(['email' => 'Email already exists!']);
            }
            $user->email = $request->email;
        }
        $user->role = $request->role;

        if ($request->password != null) {

            $user->password = Hash::make($request->password);
        }
        $user->save();
        // Emp


        $emp->phone = $request->phone;
        $emp->home_phone = $request->home_phone;
        $emp->gender = $request->gender;
        $emp->birthDate = $request->birthdate;
        $emp->role = $request->role;
        $emp->salary = Role::where('name',$request->role)->first()->salary;
        $emp->national_id = $request->national_id;
        $emp->address = $request->address;
        $emp->save();

        if ($request->hasFile('profile')) {
            if ($emp->profile != null) {
                Storage::disk('employees')->delete('/' . $emp->profile);
            }
            $photo =  Storage::disk('employees')->put('/', $request->file('profile'));
            $emp->profile = $photo;
            $emp->save();
        }

        return redirect()->back()->with('success', 'Employee Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $emp = Employee::findOrFail($id);
        if ($emp->profile != null) {
            Storage::disk('employees')->delete('/' . $emp->profile);
        }
        $user = User::findOrFail($emp->user_id);
        $user->delete();
        return to_route('employees.index', ['employees' => Employee::all()])->with('success', 'employee deleted successfully!');
    }
    public function search(Request $request)
    {
        $request->validate([
            'name' => "required"
        ]);

        $users = User::where('name', 'like', '%' . $request->name . '%')->get();
        $employees = [];

        foreach ($users as $user) {
            if ($user->employee) {
                $employees[] = [
                    'id' => $user->employee->id,
                    'profile' => $user->employee->profile,
                    'gender' => $user->employee->gender,
                    'salary' => $user->employee->salary,
                    'user' => [
                        'name' => $user->name,
                        'role' => $user->role
                    ]
                ];
            }
        }

        return response()->json($employees);
    }

    public function getAllEmployees()
    {
        $users = User::whereHas('employee')->where('id', '!=', auth()->id())->get();
        $employees = [];

        foreach ($users as $user) {
            $employees[] = [
                'id' => $user->employee->id,
                'profile' => $user->employee->profile,
                'gender' => $user->employee->gender,
                'salary' => $user->employee->salary,
                'user' => [
                    'name' => $user->name,
                    'role' => $user->role
                ]
            ];
        }

        return response()->json($employees);
    }
    // salaries
    public function salaries() {
        $total=DB::table('employees')->sum('salary')+DB::table('doctors')->sum('salary');
        $counts=[];
        $roles=Role::all();
        foreach ($roles as $role) {
            $count=DB::table('users')->where('role',$role->name)->count();
            $counts[$role->name]=[
                'count'=>$count,
                'default'=>$role->salary,
                'incentives'=>$role->incentives,
                'total'=>$count*$role->salary +$count*$role->incentives
            ];
        }
        $doctors_salary=DB::table('doctors')->sum('salary');
        $doctors=DB::table('doctors')->count();
        return view('employees.salaries',compact('total','counts','doctors','doctors_salary'));
    }
}
