<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function login(){
       
 
        if(auth()->attempt(request()->only(['email','password']),
        request()->filled('remember'))){
                    if(auth()->user()->role == 'admin' || auth()->user()->role == 'super_admin'){
                        return to_route('dashboard');

                    }else if(auth()->user()->role == 'receptionist'){
                        return to_route('reception.index');
                    }
                     else if(auth()->user()->role == 'doctor'){
                        return to_route('doctor.index');
                    }
                     else if(auth()->user()->role == 'call-center'){
                        return to_route('centers.index');
                    }
        }
        return redirect()->back()->withErrors(['email'=>'invaild email or password'])->withInput();
 
    }
   

     public function store(Request $request){
      
      
        $validator=  Validator::make($request->all(),[
            'name'=>['required','min:3'],
            'email'=>['required', 'unique:users'],
            'password'=>['required','min:8','confirmed'],
            'role'=>['required',Rule::in(['admin','employee','nurse'])],
            
        ]
        );
        
        if ($validator->fails()) {

           return redirect()->back()->withErrors($validator)->withInput();
        }
      
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
            
        ]);

       return redirect()->back()->with('success','New User regirtered successfully!');
   
    }

    public function logout(Request $request): RedirectResponse{
        Auth::guard('web')->logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/login');
    }
}
