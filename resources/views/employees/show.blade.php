@extends('layouts.main')
@section('title')Employees
@endsection
@section('content')
<div class="my-3 w-100 d-flex justify-content-between">
    <a href="{{route('employees.index')}}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>

</div>
@if (session('success'))
                
<div class="w-100 my-2">
    <p class="text-center text-success">
            {{session('success')}}
    </p>
</div>
@endif
<div class="row my-3">
    <div class="col-3">
        <img src="{{ $emp->profile ? Storage::disk('employees')->url($emp->profile) : asset('storage/profile.jfif') }}" alt="" class="w-100">
    </div>
    <div class="col">
        <form action="{{route('employees.update',$emp->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-5">
                    <label for="name" class="form-label">Name</label>
                    @error('name')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="text" name="name" class="form-control" required value="{{$emp->user->name}}">
                </div>
                <div class="col-5">
                    <label for="phone" class="form-label">Email</label>
                    @error('email')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="email" class="form-control" name="email" value="{{$emp->user->email}}">
                </div>
                <div class="col">
                    <label for="role" class="form-label"> Role</label>
                    @error('role')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                    <select class="form-select" name="role" >
                        <option disabled >role</option>
                        <option @if($emp->user->role == "admin") selected @endif   value="admin">Admin</option>
                        <option @if($emp->user->role == "receptionist") selected @endif  value="receptionist">Receptionist</option>
                        <option @if($emp->user->role == "nurse") selected @endif  value="nurse">Nurse</option>
                        <option @if($emp->user->role == "security") selected @endif  value="security">Security</option>
                       </select>
                    </div>
            </div>
            <div class="row my-3">
                <div class="col">
                    <label for="password" class="form-label">Password</label>
                    @error('password')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="password" name="password" class="form-control" placeholder="Update Password" >
                </div>

                <div class="col">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    @error('password_confirmation')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="password" name="password_confirmation" class="form-control" >
                </div>

            </div>
            <div class="row my-3">
                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    @error('phone')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="text" name="phone" class="form-control" required value="{{$emp->phone}}">
                </div>

                <div class="col">
                    <label for="home_phone" class="form-label">Home Phone</label>
                    @error('home_phone')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="text" name="home_phone" class="form-control" required value="{{$emp->home_phone}}">
                </div>

            </div>
            
            <div class="row my-3">
                <div class="col-5">
                    <label for="national_id" class="form-label">National id</label>
                    @error('national_id')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="text" name="national_id" class="form-control" value="{{$emp->national_id}}" >
                </div>
                <div class="col-5">
                    <label for="birthdate" class="form-label">birthdate</label>
                    @error('birthdate')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="date" class="form-control" name="birthdate" value="{{$emp->birthDate}}">
                </div>
                <div class="col">
                    <label for="gender" class="form-label"> Gender</label>
                    @error('gender')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                    <select class="form-select" name="gender" >
                        <option @if($emp->gender == "male") selected @endif value="male">Male</option>
                        <option @if($emp->gender =="female") selected @endif value="female">Female</option>
                       
                       </select>
                    </div>
            </div>
            <div class="row my-3">
                <div class="col-5">
                    <label for="address" class="form-label">Address</label>
                    @error('address')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                  <input type="text" name="address" class="form-control" required value="{{$emp->address}}">
                </div>
               
                <div class="col">
                    <label for="formFile" class="form-label">Profile Photo</label>
                    <input class="form-control" type="file" id="formFile" name="profile">
                  
                    </div>
            </div>

            <div class="mt-4 d-flex w-100 justify-content-end">
                <button class="btn btn-primary" type="submit">Update</button>
            </div>

            
        </form>
    </div>

</div>
<div class="my-2 d-flex justify-content-end">
  <form action="{{route('employees.destroy',$emp->id)}}" method="post">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger" type="submit">Delete</button>
  </form>
</div>


@endsection
