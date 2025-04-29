@extends('layouts.main')
@section('title')
    Events
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2">Events</h1>
    </div>
    @include('inc.alerts')
    <div class="events d-flex flex-wrap gap-3">
        @foreach ($events as $event)
            <div class="card" style="width: 21rem;">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="h6">Event {{ $event->id }}</h6>
                    <p class="text-muted"> {{ Carbon\Carbon::create($event->created_at)->diffForHumans() }} </p>
                </div>
                <div class="card-body">
                    <h5 class="card-title"> {{ $event->title }}</h5>
                    <div class="row my-2">
                        <div class="col-8 align-items-center">
                           <p>
                            {{$event->description}}
                           </p>
                        </div>
                        
                    </div>

                </div>
                <div class="card-footer">
                    <div class="d-flex w-100 justify-content-end align-items-end">
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary justify-self-end">show ({{count($event->followers)}})</a>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
