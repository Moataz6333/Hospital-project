@extends('layouts.app')
@section('title') Register @endsection
@section('content')
<h1 class="text-center my-3">Register new User</h1>
        @if (session('success'))
        <p class="my-2 text-center text-success">{{session('success')}} </p>
        @endif

        <div class=" my-3 w-100 ">
            <form class="w-75 mx-auto" method="POST" action="{{route('users.store')}}">
                @csrf
                <div class="mb-3">
                  <label for="name" class="form-label">Name </label>
                  @error('name')
                  <p class="text-danger "><small>{{$message }}</small>  </p>
                  @enderror
                  <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email </label>
                  @error('email')
                  <p class="text-danger "><small>{{$message }}</small>  </p>
                  @enderror
                  <input type="email" name="email" class="form-control" id="email" required >
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  @error('password')
                  <p class="text-danger "><small>{{$message }}</small>  </p>
                  @enderror
                  <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                  <label for="password_confirmation" class="form-label">Password Confirmation</label>
                  <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <div class=" mb-3">
                    @error('role')
                    <p class="text-danger "><small>{{$message }}</small>  </p>
                    @enderror
                    <select class="form-select" name="role" required>
                        <option value="admin" selected>admin</option>
                        <option value="employee">employee</option>
                        <option value="nurse">nurse</option>
                      </select>

                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
              </form>

        </div>
    
@endsection