@extends('layouts.main')
@section('title')
    Doctors
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><a href="{{ route('doctors.index') }}" class="btn btn-dark"><span
                    data-feather="arrow-left"></span></a> Edit Doctor : {{ $doctor->name }} </h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif
    <div class="row my-3">
        <div class="col-3">
            <img src="{{ $doctor->profile ? Storage::disk('doctors')->url($doctor->profile) : asset('storage/profile.jfif') }}"
                alt="" class="w-100">
        </div>
        <div class="col">
            <form action="{{ route('doctors.update', $doctor->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <label for="name" class="form-label">Name</label>
                        @error('name')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="text" name="name" class="form-control" required
                            value="{{ $doctor->user->name }}">
                    </div>
                    <div class="col">
                        <label for="phone" class="form-label">Email</label>
                        @error('email')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="email" class="form-control" name="email" value="{{ $doctor->user->email }}">
                    </div>

                </div>
                <div class="row ">
                    <div class="col">
                        <label for="password" class="form-label">Password</label>
                        @error('password')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="password" name="password" class="form-control" placeholder="Update Passowrd">
                    </div>

                    <div class="col">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        @error('password_confirmation')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                </div>
                <div class="row">

                    <div class="col">
                        <label for="phone" class="form-label">phone</label>
                        @error('phone')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="text" class="form-control" name="phone" value="{{ $doctor->phone }}">
                    </div>
                    <div class="col">
                        <label for="formFile" class="form-label">Profile Photo</label>
                        <input class="form-control" type="file" id="formFile" name="profile">
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="national_id" class="form-label"> specialty</label>
                        @error('specialty')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <select class="form-select" name="specialty" aria-label="Default select example">
                            <option value="" disabled>Choose a specialty</option>
                            <option value="cardiology" @if ($doctor->specialty == 'cardiology') selected @endif>Cardiology</option>
                            <option value="dermatology" @if ($doctor->specialty == 'dermatology') selected @endif>Dermatology
                            </option>
                            <option value="endocrinology" @if ($doctor->specialty == 'endocrinology') selected @endif>Endocrinology
                            </option>
                            <option value="gastroenterology" @if ($doctor->specialty == 'gastroenterology') selected @endif>
                                Gastroenterology
                            </option>
                            <option value="neurology" @if ($doctor->specialty == 'neurology') selected @endif>Neurology</option>
                            <option value="oncology" @if ($doctor->specialty == 'oncology') selected @endif>Oncology</option>
                            <option value="ophthalmology" @if ($doctor->specialty == 'ophthalmology') selected @endif>Ophthalmology
                            </option>
                            <option value="orthopedics" @if ($doctor->specialty == 'orthopedics') selected @endif>Orthopedics
                            </option>
                            <option value="pediatrics" @if ($doctor->specialty == 'pediatrics') selected @endif>Pediatrics</option>
                            <option value="psychiatry" @if ($doctor->specialty == 'psychiatry') selected @endif>Psychiatry
                            </option>
                            <option value="radiology" @if ($doctor->specialty == 'radiology') selected @endif>Radiology</option>
                            <option value="urology" @if ($doctor->specialty == 'urology') selected @endif>Urology</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="birthdate" class="form-label">experiance</label>
                        @error('experiance')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <textarea class="form-control" name="experiance">{{ $doctor->experiance }} </textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="national_id" class="form-label"> Clinic</label>
                        @error('clinic_id')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <select class="form-select" name="clinic_id">
                            <option value="" disabled>Choose a Clinic</option>
                            @foreach ($clinics as $clinic)
                                <option value={{ $clinic->id }} @if ($doctor->clinic_id == $clinic->id) selected @endif>
                                    {{ $clinic->name }}</option>
                            @endforeach

                        </select>
                    </div>


                    <div class="col">
                        <label for="salary" class="form-label">salary</label>
                        @error('salary')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="text" name="salary" class="form-control" value="{{ $doctor->salary }}">
                    </div>
                    <div class="col">
                        <label for="price" class="form-label">price</label>
                        @error('price')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="text" name="price" class="form-control" value="{{ $doctor->price }}">
                    </div>

                </div>

                <div class="mt-3 d-flex w-100 justify-content-end">

                    <button type="submit" class="btn btn-primary ">Update</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
