@extends('layouts.main')
@section('title')Hospital
@endsection
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Hospital</h1>
</div>
            @if (session('success'))
                
            <div class="w-100 my-2">
                <p class="text-center text-success">
                        {{session('success')}}
                </p>
            </div>
            @endif
    <form action="{{route('hospital.update')}}" method="post">
        @csrf
        <div class="row">
            <div class="col">
                <label for="name" class="form-label">Hospital Name</label>
                @error('name')
                <p class="text-danger "><small>{{$message }}</small>  </p>
                @enderror
              <input type="text" name="name" class="form-control" value="{{$hospital->name}}">
            </div>
            <div class="col">
                <label for="address" class="form-label">Address</label>
                @error('address')
                <p class="text-danger "><small>{{$message }}</small>  </p>
                @enderror
              <input type="text" class="form-control" name="address" value="{{$hospital->address}}">
            </div>
          </div>
        <div class="row">
            <div class="col">
                <label for="phone1" class="form-label">Phone 1 </label>
                @error('phone1')
                <p class="text-danger "><small>{{$message }}</small>  </p>
                @enderror
              <input type="text" name="phone1" class="form-control" value="{{$hospital->phone1}}" >
            </div>
            <div class="col">
                <label for="phone2" class="form-label">Phone 2</label>
                @error('phone2')
                <p class="text-danger "><small>{{$message }}</small>  </p>
                @enderror
              <input type="text" class="form-control" name="phone2" value="{{$hospital->phone2}}">
            </div>
          </div>
        <div class="row">
            <div class="col">
                <label for="email" class="form-label">Email  </label>
                @error('email')
                <p class="text-danger "><small>{{$message }}</small>  </p>
                @enderror
              <input type="email" name="email" class="form-control" value="{{$hospital->email}}">
            </div>
            <div class="col">
                <label for="hotline" class="form-label">Hotline </label>
                @error('hotline')
                <p class="text-danger "><small>{{$message }}</small>  </p>
                @enderror
              <input type="text" class="form-control" name="hotline" value="{{$hospital->hotline}}">
            </div>
          </div>
          <div class="mt-3 d-flex w-100 justify-content-end">
              <button class="btn btn-primary ">Update</button>
          </div>
    </form>
    
@endsection