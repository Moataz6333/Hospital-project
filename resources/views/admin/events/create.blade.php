@extends('layouts.main')
@section('title')
    Events
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2">Create Event</h1>
    </div>
    @include('inc.alerts')

    <div class="my-3">
        <form action="{{ route('events.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col">
                    <div>
                        <label for="title" class="form-label">Title</label>
                        @error('title')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="text" name="title" required placeholder="Event title" class="form-control">
                       
                    </div>
                   <div class="my-3">
                        <label for="description" class="form-label">description</label>
                        @error('description')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <textarea class="form-control" name="description" id="description" cols="30" rows="4" placeholder="description"></textarea>
                    </div>
                </div>
                <div class="col">
                    <label for="date" class="form-label">Date</label>
                    @error('date')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="datetime-local" name="date" required  class="form-control">
               
                </div>
                <div class="col">
                    <div class="d-flex w-100 align-items-end" style="margin-top: 27px;">
                        <button class="btn btn-primary" type="submit">create</button>
                    </div>
                </div>
            </div>
           
          
           
        </form>
    </div>
    
@endsection
