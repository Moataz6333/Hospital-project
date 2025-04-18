@extends('layouts.main')
@section('title')
    Clinics
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><a href="{{route('clinics.index')}}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>  edit clinic : {{ $clinic->name }}</h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif

    <div class="my-3 w-100">
        @error('name')
            <p class="text-danger "><small>{{ $message }}</small> </p>
        @enderror
        @error('floor')
            <p class="text-danger "><small>{{ $message }}</small> </p>
        @enderror
        @error('description')
            <p class="text-danger "><small>{{ $message }}</small> </p>
        @enderror
        @error('photo')
            <p class="text-danger "><small>{{ $message }}</small> </p>
        @enderror
        <form action="{{ route('clinics.update', $clinic->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-auto">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" value="{{ $clinic->name }}" required name="name"
                        placeholder="Clinic Name">
                </div>
                <div class="col-auto">
                    <label for="place" class="form-label">Place</label>
                    <input type="text" class="form-control" value="{{ $clinic->place }}" required name="floor"
                        placeholder="Clinic Floor">
                </div>
                <div class="col">
                    <label for="description" class="form-label">description</label>
                    <textarea class="form-control" name="description" id="description" cols="30">{{ $clinic->description }}</textarea>
                </div>
            </div>
            <div class="row">
             
                <div class="col-3">
                    <img src="{{ $clinic->photo ? Storage::disk('clinics')->url($clinic->photo) : asset('storage/profile.jfif') }}" alt="" style="width: 100%; height:100%;">
                </div>
                <div class="col">
                    <label for="photo" class="form-label">Photo</label>
                    <input class="form-control" type="file"  name="photo">
                </div>
            </div>
            <div class="w-100 d-flex justify-content-end">
                <button class="btn btn-primary col-1">Update</button>
            </div>
        </form>
    </div>
    <hr>

    @if (count($clinic->doctors) != 0)
        <h2 class="h2 my-3">Doctors</h2>
        @foreach ($clinic->doctors as $key => $doctor)
            <p> <strong>{{ $key + 1 }}. {{ $doctor->user->name }} </strong> </p>
        @endforeach
    @endif


@endsection
