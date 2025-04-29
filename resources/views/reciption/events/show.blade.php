@extends('layouts.recip')
@section('title')
    Events
@endsection
@section('content')
    <div class="d-flex gap-3 flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <a href="{{route('reception.events')}}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
        <h1 class="h2">Event : {{$event->title}}</h1>
    </div>
    @include('inc.alerts')
    <div class="my-3">
            <form class="row d-flex justify-content-center" action="{{route('reception.event.register',$event->id)}}" method="post">
                @csrf
                <div class="col-5">
                    <input type="text" placeholder="Email ..." name="email" required class="form-control">
                </div>
                <div class="col-1">
                    <button class="btn btn-primary" type="submit">register</button>
                </div>
            </form>
    </div>
    <hr>
    <div class="my-3">
        <h3 class="h3 mb-3">Followers :</h3>
            <ol>
                @foreach ($event->followers as $follower)
                    <li class="h4">
                        <h4 class="h4 text-primary">{{$follower->email}} </h4>
                    </li>
                @endforeach
            </ol>
    </div>

@endsection
