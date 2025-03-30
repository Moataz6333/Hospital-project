<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\User;
use App\Models\TimeTable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\PatienInterface;
use App\Models\Appointment;
use Carbon\Carbon;



class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $patienService;
    public function __construct(PatienInterface $patienService)
    {
        $this->patienService = $patienService;
    }
    public function index()
    {
        // $this->setDate();
        return view('admin.doctors.index', ['doctors' => Doctor::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clinics = Clinic::all();
        return view('admin.doctors.create', ['clinics' => $clinics]);
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
                'password' => ['required', 'min:6', 'confirmed'],
                'specialty' => ['required'],
                'national_id' => ['required'],

                'birthdate' => ['required'],
                'experiance' => ['required'],
                'salary' => ['required'],

                'phone' => ['required', 'max:13'],
                'clinic_id' => ['required', 'exists:clinics,id'],
                'profile' => ['image', 'max:2048'],

            ]
        );
        $photo = null;

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->hasFile('profile')) {

            $photo =  Storage::disk('doctors')->put('/', $request->file('profile'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor'

        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'national_id' => $request->national_id,
            'specialty' => $request->specialty,
            'experiance' => $request->experiance,
            'salary' => (int) $request->salary,
            'price' => (int) $request->price,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
            'clinic_id' => $request->clinic_id,
            'profile' => $photo
        ]);


        return redirect()->back()->with('success', 'Doctor added successfully!');
    }

    public function edit(string $id)
    {
        return view('admin.doctors.edit', ['doctor' => Doctor::findOrFail($id), 'clinics' => Clinic::all()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $doctor = Doctor::findOrFail($id);
        $doctor->salary = (int) $request->salary;
        $doctor->price = (int) $request->price;

        $validator =  Validator::make(
            $request->all(),
            [
                'name' => ['required', 'min:3'],
                'email' => ['required'],
                'password' => ['nullable', 'min:6', 'confirmed'],
                'specialty' => ['required'],
                'phone' => ['required', 'max:13'],
                'clinic_id' => ['required', 'exists:clinics,id'],
                'profile' => ['image', 'max:2048']

            ]
        );


        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('profile')) {
            if ($doctor->profile != null) {
                Storage::disk('doctors')->delete('/' . $doctor->profile);
            }

            $photo =  Storage::disk('doctors')->put('/', $request->file('profile'));
            $doctor->profile = $photo;
        }
        $user = User::findOrFail($doctor->user_id);
        $user->name = $request->name;
        // email
        if ($user->email != $request->email) {
            $email = User::where('email', $request->email)->exists();
            if ($email) {

                return back()->withErrors(['email' => 'Email already exists!']);
            }
            $user->email = $request->email;
        }
        // password
        if ($request->password != null) {

            $user->password = Hash::make($request->password);
        }
        $user->save();

        $doctor->specialty = $request->specialty;
        $doctor->experiance = $request->experiance;
        $doctor->phone = $request->phone;
        $doctor->clinic_id = $request->clinic_id;

        $doctor->save();

        return redirect()->back()->with('success', 'Doctor Updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        if ($doctor->profile) {
            Storage::disk('doctors')->delete('/' . $doctor->profile);
        }
        $doctor->delete();
        return redirect()->back()->with('success', 'Doctor deleted successfully!');
    }

    // timeTable view 
    public function timeTable($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctors.timeTable', ['doctor' => $doctor]);
    }
    // update time table 
    public function timeUpdate(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        TimeTable::updateOrCreate(
            ['doctor_id' => $id],
            $request->except('_token')
        );
        return redirect()->back()->with('success', 'time table updated successfully');
    }
    // set defaut time
    public function setDate()
    {
        $doctors = Doctor::all();
        foreach ($doctors as $doctor) {
            TimeTable::create([
                'doctor_id' => $doctor->id,
                'sun_start' => '12:00:00',
                'sun_end' => '22:00:00',
                'mon_start' => '12:00:00',
                'mon_end' => '22:00:00',
                'tue_start' => '12:00:00',
                'tue_end' => '22:00:00',
                'wed_start' => '12:00:00',
                'wed_end' => '22:00:00',
                'thurs_start' => '12:00:00',
                'thurs_end' => '22:00:00'


            ]);
        }
    }
    public function search(Request $request)
    {
        $request->validate([
            'name' => "required"
        ]);

        $users = User::where('role', 'doctor')
            ->where('name', 'like', '%' . $request->name . '%')
            ->get();

        $doctors = [];
        foreach ($users as $user) {
            if ($user->doctor) {
                $doctor = $user->doctor;
                $doctor->load('clinic', 'user'); // Load related clinic and user data
                $doctors[] = $doctor;
            }
        }

        return response()->json($doctors);
    }

    public function getAllDoctors()
    {
        $doctors = Doctor::with('clinic', 'user')->get(); // Eager load relationships
        return response()->json($doctors);
    }
    //view old appointments
    public function archive($id, $current = null)
    {
        $doctor = Doctor::findOrFail($id);
        $days = GetDaysNames(TimesWhereNotNull($doctor));
        if ($current == null) {
            $current = GetCurrentDay($days);
        }
        $appointments = $this->patienService->archive($doctor, $current);
        return view('admin.doctors.archive', compact('doctor', 'appointments', 'days', 'current'));
    }
     // show appointment
     public function appointment($id,$from=null)  {
        
        $appointment = Appointment::findOrFail($id);
        $times = TimesWhereNotNull($appointment->doctor);


        $AllDays = ['sat' => 'Saturday', 'sun' => 'Sunday', 'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thurs' => 'Thursday', 'fri' => 'Friday'];
        $days = [];
        foreach ($times as $key => $date) {
            $times[$key] = Carbon::createFromFormat('H:i:s', $date)->format('h:i A');
        }

        foreach ($times as $key => $val) {
            $subString = substr($key, 0, 3); //sun
            if ($subString == 'thu') {
                $subString = "thurs";
            }

            if (!array_key_exists($subString, $days)) {
                $days[$subString] = $AllDays[$subString];
            }
        }
        return view('admin.doctors.showAppointment',compact('appointment','times', 'days','from'));
    }
}
