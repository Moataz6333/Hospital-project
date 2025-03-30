@extends('layouts.main')
@section('title') Clinics
@endsection
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"> edit clinic : {{$clinic->name}}</h1>
</div>
            @if (session('success'))
                
            <div class="w-100 my-2">
                <p class="text-center text-success">
                        {{session('success')}}
                </p>
            </div>
            @endif

            <div class="my-3 w-100">
                         @error('name')
                        <p class="text-danger "><small>{{$message }}</small>  </p>
                        @enderror
                         @error('floor')
                        <p class="text-danger "><small>{{$message }}</small>  </p>
                        @enderror
                <form action="{{route('clinics.update',$clinic->id)}}" method="post" class="row">
                    @csrf
                    @method('PUT')
                    <div class="col-auto">
                       
                        <input type="text" class="form-control" value="{{$clinic->name}}" required name="name" placeholder="Clinic Name">
                      </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" value="{{$clinic->place}}"  required name="floor" placeholder="Clinic Floor">
                      </div>
                      <button class="btn btn-primary col-1">Update</button>
                </form>
            </div>
            <hr>

            @if (count($clinic->doctors) !=0)
                
            <h2 class="h2 my-3">Doctors</h2>
            @foreach ($clinic->doctors as $key => $doctor)
            
            <p> <strong>{{$key + 1}}. {{$doctor->name}} </strong>  </p>
            
            @endforeach
            
            @endif
            

            @endsection