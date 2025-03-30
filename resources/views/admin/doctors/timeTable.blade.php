@extends('layouts.main')
@section('title')
    Doctors
@endsection
@section('content')
    <div class="d-flex flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <a href="{{ route('doctors.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
        <h1 class="h2 mx-3"> doctor : {{ $doctor->name }}</h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif


    <div class="my-2 border-bottom " style="width: fit-content">
        <h2 class="h2">Time Table</h2>
    </div>

    <form action="{{ route('doctors.timeUpdate', $doctor->id) }}" method="post">
        @csrf
        @php
            $timeTable = $doctor->timeTable ?? null;
        @endphp

        @foreach (['sat' => 'Saturday', 'sun' => 'Sunday', 'mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thurs' => 'Thursday', 'fri' => 'Friday'] as $key => $day)
            <div class="my-4 row flex-column flex-md-row gap-3">
                <div class="col-auto align-content-center">
                    <h5>{{ $day }}</h5>
                </div>
                <div class="col-auto ">
                    <label>From</label>
                    <input type="time" class="form-control" name="{{ $key }}_start"
                        id="{{ $key }}_start" value="{{ $timeTable ? $timeTable->{$key . '_start'} : '' }}">
                </div>
                <div class="col-auto align-content-center">
                    <label>To</label>
                    <input type="time" class="form-control" name="{{ $key }}_end" id="{{ $key }}_end"
                        value="{{ $timeTable ? $timeTable->{$key . '_end'} : '' }}">
                </div>
                <div class="col-auto align-content-end">
                    <button type="button" class="btn btn-danger" onclick="resetTime('{{ $key }}')">Off</button>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end my-3">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>







    <script>
        function resetTime(day) {
            document.getElementById(day + '_start').value = "";
            document.getElementById(day + '_end').value = "";
        }
    </script>
@endsection
