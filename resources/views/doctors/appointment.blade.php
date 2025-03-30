@extends('layouts.doctor')
@section('title')
    Appointment
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href="@if ($from == 'archive') {{ route('doctor.archive', ['day' => GetCurrentDayForDoctor($appointment->doctor)]) }}  @else
        {{ route('doctor.index', ['day' => GetCurrentDayForDoctor($appointment->doctor)]) }} @endif 
        "
                class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            Appointment for <u>{{ $appointment->patien->name }}</u>
        </h2>
        <hr>


    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif
    <div class="my-3">

        <div class="card">
            <h5 class="card-header">Appointment</h5>
            <div class="card-body">
                <div class="row my-3 flex-column flex-md-row">
                    <div class="col d-flex gap-2 align-items-end">
                        <h5 class="card-title m-0 font-weight-600">Name : </h5>
                        <p class="card-text" style="font-size: 1rem;">{{ $appointment->patien->name }} </p>
                    </div>
                    <div class="col d-flex gap-2 align-items-end ">
                        <h5 class="card-title m-0 font-weight-600">Phone : </h5>
                        <p class="card-text" style="font-size: 1rem;">{{ $appointment->patien->phone }} </p>
                    </div>
                </div>
                <div class="row my-3 flex-column flex-md-row">
                    <div class="col d-flex gap-2 align-items-end">
                        <h5 class="card-title m-0 font-weight-600">Clinic : </h5>
                        <p class="card-text" style="font-size: 1rem;">{{ $appointment->doctor->clinic->name }} </p>
                    </div>
                    <div class="col d-flex gap-2 align-items-end">
                        <h5 class="card-title m-0 font-weight-600">Doctor : </h5>
                        <p class="card-text" style="font-size: 1rem;">{{ $appointment->doctor->user->name }} </p>
                    </div>
                </div>
                <div class="row my-3 flex-column flex-md-row">
                    <div class="col d-flex gap-2 align-items-end ">
                        <h5 class="card-title m-0 font-weight-600">Date : </h5>
                        <p class="card-text" style="font-size: 1rem;">
                            {{ date_create($appointment->date)->format('l d-m-Y') }} </p>
                    </div>
                    <div class="col d-flex gap-2 align-items-end">
                        <h5 class="card-title m-0 font-weight-600">Appointment Type : </h5>
                        <p class="card-text" style="font-size: 1rem;">
                            @if ($appointment->type == 'examination')
                                examination - كشف
                            @else
                                consultation - استشاره
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row my-3 flex-column flex-md-row">
                    <div class="col d-flex gap-2 align-items-end">
                        <h5 class="card-title m-0 font-weight-600">Status : </h5>
                        @if ($appointment->status == 'canceled')
                            <p class="card-text text-danger" style="font-size: 1rem;">{{ $appointment->status }} by
                                {{ $appointment->canceled_by }} </p>
                        @else
                            <p class="card-text text-primary" style="font-size: 1rem;">{{ $appointment->status }} </p>
                        @endif
                    </div>
                    <div class="col d-flex gap-2 align-items-end ">
                        <h5 class="card-title m-0 font-weight-600">Paid : </h5>
                        <p class="card-text" style="font-size: 1rem;">
                            @if ($appointment->paid)
                                <span class="text-success">Yes</span>
                            @else
                                <span class="text-danger">No</span>
                            @endif
                        </p>
                    </div>

                </div>
            </div>
        </div>
        @if ($appointment->status != 'done' && $appointment->status != 'canceled')
            <div class="my-3 d-flex w-100 @if ($appointment->paid && $appointment->status != 'done') justify-content-between @endif">
                <a href="{{ route('appointment.doctor.cancel', $appointment->id) }}" class="btn btn-danger"
                    onclick="return confirm('Are you sure you want to cancel?');">Cancel</a>
                @if ($appointment->paid && $appointment->status != 'done')
                    <a href="{{ route('appointment.done', $appointment->id) }}" class="btn btn-success ">Done</a>
                @endif
            </div>
        @endif
    </div>
@endsection
