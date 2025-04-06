@extends('layouts.main')
@section('title')
    Roles
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Roles</h1>
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
        <form action="{{ route('roles.store') }}" method="post" class="row">
            @csrf
            <div class="col-auto">

                <input type="text" class="form-control" required name="name" placeholder="Name">
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" required name="salary" placeholder="Salary">
            </div>
            <div class="col-auto">
                <input type="text" class="form-control"  name="incentives" placeholder="Incentives">
            </div>
            <button class="btn btn-primary col-1">Create</button>
        </form>
    </div>
    <hr>
    <h5 class="my-3">All roles</h5>

    <div class="w-100 d-flex gap-4 flex-wrap">
        @foreach ($roles as $role)
            <div class="card" style="width: 18rem; ">

                <div class="card-body">
                    <h5 class="card-title">{{ $role->name }} </h5>
                    <p class="card-text">
                        <strong>salary</strong> : {{ $role->salary }} <br>
                        <strong>incentives</strong> : {{ $role->incentives }} <br>
                        <strong>count</strong> : {{App\Models\User::where('role',$role->name)->count()}} <br>
                    </p>
                    <div class="d-flex w-100 justify-content-between">
                        <form action="{{ route('roles.destroy', $role->id) }}" method="post"
                            onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('roles.edit', $role->id) }} " class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
