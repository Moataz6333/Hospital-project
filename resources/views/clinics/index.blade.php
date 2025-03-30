@extends('layouts.main')
@section('title') Clinics
@endsection
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"> Clinics</h1>
</div>
            @if (session('success'))
                
            <div class="w-100 my-2">
                <p class="text-center text-success">
                        {{session('success')}}
                </p>
            </div>
            @endif

            <div class="my-3 w-100">
                <h5>Add new Clinic</h5>
                         @error('name')
                        <p class="text-danger "><small>{{$message }}</small>  </p>
                        @enderror
                         @error('floor')
                        <p class="text-danger "><small>{{$message }}</small>  </p>
                        @enderror
                <form action="{{route('clinics.store')}}" method="post" class="row">
                    @csrf
                    <div class="col-auto">
                       
                        <input type="text" class="form-control" required name="name" placeholder="Clinic Name">
                      </div>
                    <div class="col-auto">
                        <input type="text" class="form-control" required name="floor" placeholder="Clinic Floor">
                      </div>
                      <button class="btn btn-primary col-1">Create</button>
                </form>
            </div>
            <hr>
            <h5 class="my-3">All Clinics</h5>

            <div class="w-100 d-flex gap-4 flex-wrap">
            @foreach ($clinics as $clinic)
            <div class="card" style="width: 18rem; ">
               
                <div class="card-body">
                  <h5 class="card-title">{{$clinic->name}} </h5>
                  <p class="card-text">
                   <strong>floor</strong>  : {{$clinic->place}} <br>
                    <strong>doctors</strong>  : {{count($clinic->doctors)}} <br>
                  </p>
                  <div class="d-flex w-100 justify-content-between">
                    <form action="{{route('clinics.destroy',$clinic->id)}}" method="post"  onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <a href="{{route('clinics.edit',$clinic->id)}} " class="btn btn-primary">Show</a>
                  </div>
                </div>
         </div>
         
         @endforeach
         
        </div>
    
@endsection