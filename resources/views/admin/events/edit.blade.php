@extends('layouts.main')
@section('title')
    Events
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div class="d-flex gap-3">
            <a href="{{ route('events.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            <h1 class="h2">update Event {{ $event->id }} </h1>
        </div>
        <div class=" d-flex">
            <form action="{{ route('events.destroy', $event->id) }}" method="post" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit"><span data-feather="trash-2"></span></button>
            </form>

        </div>
    </div>
    @include('inc.alerts')

    <div class="my-3">
        <form action="{{ route('events.update', $event->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col">
                    <div>
                        <label for="title" class="form-label">Title</label>
                        @error('title')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <input type="text" value="{{ $event->title }}" name="title" required placeholder="Event title"
                            class="form-control">

                    </div>
                    <div class="my-3">
                        <label for="description" class="form-label">description</label>
                        @error('description')
                            <p class="text-danger "><small>{{ $message }}</small> </p>
                        @enderror
                        <textarea class="form-control" name="description" id="description" cols="30" rows="4"
                            placeholder="description">{{ $event->description }} </textarea>
                    </div>
                </div>
                <div class="col">
                    <label for="date" class="form-label">Date</label>
                    @error('date')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="datetime-local" value="{{ $event->date }}" name="date" required class="form-control">

                </div>
                <div class="col">
                    <div class="d-flex w-100 align-items-end" style="margin-top: 27px;">
                        <button class="btn btn-success" type="submit">update</button>
                    </div>
                </div>
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
