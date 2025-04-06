<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Employee;
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
        return view('employees.index', ['employees' => Employee::all()->load('user')]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('employees.create');
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
                'role' => ['required', Rule::in(['admin', 'super_adimn', 'receptionist', 'nurse', 'security'])],
                'password' => ['required', 'min:6', 'confirmed'],
                'national_id' => ['required', 'min:5'],
                'salary' => ['required'],
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
        $emp->birthDate = $request->birthdate;
        $emp->salary = (int) $request->salary;
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

        $request->validate([
            "name" => "required|min:2",
            "email" => "required|email",
            "role" => "required|min:4",
            "password" => "nullable|min:6|confirmed",
            "national_id" => "required",
            "address" => "required",
            "salary" => "required",
            "phone" => "required",
            "home_phone" => "required",
            "gender" => "required",
            "profile" => "image|max:2048|nullable"
        ]);

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
        $emp->salary = (int) $request->salary;
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
}
