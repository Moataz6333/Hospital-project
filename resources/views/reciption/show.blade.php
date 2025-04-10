@extends('layouts.recip')
@section('title')
    Reciptionist
@endsection
@section('content')
    <div class="my-3">
        <h2 class="h2">
            <a href="{{ route('reception.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            {{ $clinic->name }}
        </h2>
        <span class="text-muted">{{ $clinic->place }} </span>
        <hr>
    </div>
    <h3 class="my-2">Available Doctors</h3>
    <div class="my-2 d-flex flex-wrap gap-3 w-100">
        @foreach ($clinic->doctors as $doctor)
            @if ($doctor->timeTable)
                <div class="card border border-secondary" style="width: 28rem;">
                    <div class="card-body ">
                        <h5 class="card-title">{{ $doctor->user->name }} </h5>
                        <div class="row justify-content-between w-100 my-3">
                            <div class="col">
                                <p class="card-text">
                                    <strong>Price</strong> : {{ $doctor->price }} <br>
                                    <strong>specialty</strong> : {{ $doctor->specialty }} <br>
                                    <strong>experiance</strong> : {{ $doctor->experiance }} <br>

                                </p>
                            </div>
                            <div class="col d-flex justify-content-end ">
                                <img src="{{ $doctor->profile ? Storage::disk('doctors')->url($doctor->profile) : asset('storage/profile.jfif') }}"
                                    style=" width:80px; height:80px; border-radius:50%;     object-fit: cover;
                              object-position: center;"
                                    alt="...">
                            </div>
                        </div>
                        <div class="d-flex w-100 justify-content-between flex-wrap gap-3">
                            <div>
                                <a href="{{ route('timeTable.show', $doctor->id) }}" class="btn btn-success">Time Table</a>
                                <a href="{{ route('reception.appointments', ['id' => $doctor->id, 'day' => GetCurrentDayForDoctor($doctor)]) }}"
                                    class="btn btn-dark">Appointments</a>
                                    <a href="{{ route('registerForm', $doctor->id) }}" class="btn btn-primary">Register</a>
                            </div>
                            <div>
                                <a href="{{ route('reception.archive', $doctor->id) }}" class="btn btn-secondary">Archive</a>
                                <a href="{{ route('reception.transactions', $doctor->id) }}"
                                    class="btn btn-info">Transactions</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
