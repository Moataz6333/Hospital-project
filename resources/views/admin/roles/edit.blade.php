@extends('layouts.main')
@section('title')
    Roles
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Role {{ $role->name }} </h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif
    <div class="my-3 w-100">
        <h5>Add new Role</h5>
        @error('name')
            <p class="text-danger "><small>{{ $message }}</small> </p>
        @enderror
        @error('salary')
            <p class="text-danger "><small>{{ $message }}</small> </p>
        @enderror
        @error('incentives')
            <p class="text-danger "><small>{{ $message }}</small> </p>
        @enderror
        <form action="{{ route('roles.update',$role->id) }}" method="post" class="row">
            @csrf
            @method('PUT')
            <div class="col-auto">

                <input type="text" class="form-control" required name="name" value="{{$role->name}}" placeholder="Name">
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" required name="salary" value="{{$role->salary}}" placeholder="Salary">
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" name="incentives" value="{{$role->incentives}}" placeholder="Incentives">
            </div>
            <button class="btn btn-success col-1">Update</button>
        </form>
    </div>
@endsection
