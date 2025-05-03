@extends('layouts.main')
@section('title')
    Employees
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add New Employee</h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif

    <form action="{{ route('employees.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-5">
                <label for="name" class="form-label">Name</label>
                @error('name')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-5">
                <label for="phone" class="form-label">Email</label>
                @error('email')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="email" class="form-control" name="email">
            </div>
            <div class="col">
                <label for="role" class="form-label"> Role</label>
                @error('role')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <select class="form-select" name="role">
                    <option disabled selected>role</option>
                        @foreach ($roles as $role)
                        @if ($role->name == 'super_admin' ||$role->name == 'admin' )
                        {{-- authorize --}}
                        @can('isSuperAdmin')
                        <option value="{{$role->name}}">{{ucfirst($role->name)}} </option>
                        @endcan
                        @else
                        <option value="{{$role->name}}">{{ucfirst($role->name)}} </option>
                        @endif
                        @endforeach
                </select>
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
                <label for="password" class="form-label">Password</label>
                @error('password')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                @error('password_confirmation')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

        </div>
        <div class="row my-3">
            <div class="col">
                <label for="phone" class="form-label">Phone</label>
                @error('phone')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="col">
                <label for="home_phone" class="form-label">Home Phone</label>
                @error('home_phone')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="home_phone" class="form-control" required>
            </div>

        </div>

        <div class="row my-3">
            <div class="col-5">
                <label for="national_id" class="form-label">National id</label>
                @error('national_id')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="national_id" class="form-control">
            </div>
            <div class="col-5">
                <label for="birthdate" class="form-label">birthdate</label>
                @error('birthdate')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="date" class="form-control" name="birthdate">
            </div>
            <div class="col">
                <label for="gender" class="form-label"> Gender</label>
                @error('gender')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <select class="form-select" name="gender">
                    <option disabled selected>gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>

                </select>
            </div>
        </div>
        <div class="row my-3">
            <div class="col-5">
                <label for="address" class="form-label">Address</label>
                @error('address')
                    <p class="text-danger "><small>{{ $message }}</small> </p>
                @enderror
                <input type="text" name="address" class="form-control" required>
            </div>
           
            <div class="col">
                <label for="formFile" class="form-label">Profile Photo</label>
                <input class="form-control" type="file" id="formFile" name="profile">

            </div>
        </div>

        <div class="mt-4 d-flex w-100 justify-content-end">
            <button class="btn btn-primary" type="submit">Create</button>
        </div>


    </form>
@endsection
