@extends('layouts.recip')
@section('title')
    Appointments
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href="{{ route('clinic.show', $doctor->clinic_id) }}" class="btn btn-dark"><span
                    data-feather="arrow-left"></span></a>
            dr. <u>{{ $doctor->user->name }}'s</u> Appointments
        </h2>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                week
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item"
                        href="{{ route('reception.appointments', ['id' => $doctor->id, 'day' => $current, 'week' => 'this']) }}">(current)</a>
                </li>
                <li><a class="dropdown-item"
                        href="{{ route('reception.appointments', ['id' => $doctor->id, 'day' => $current, 'week' => 'next']) }}">(next
                        week)</a></li>
            </ul>
        </div>
        <p class="text-muted">{{ $week . ' ' . $current . ' ' . $date }} </p>
    </div>
    <hr>
    {{-- days --}}
    <div class="d-flex justify-content-evenly w-100 my-3">
        @foreach ($days as $key => $val)
            <a href="{{ route('reception.appointments', ['id' => $doctor->id, 'day' => $val]) }}"
                class="btn @if ($val == $current) btn-secondary @else btn-dark @endif">{{ $val }} </a>
        @endforeach
    </div>
    {{-- appointments --}}
    @if (count($appointments) != 0)
        <table class="table table-striped my-4">
            <thead class="table-dark">
                <tr>
                    <th>Number</th>
                    <th>
                        Name
                    </th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Payment Method</th>
                    <th>Paid</th>
                    <th>Status</th>
                    <th>Show</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($appointments as $appointment)
                    <tr>
                        <th scope="row">
                            {{ $i++ }}
                        </th>
                        <td>
                            {{ $appointment->patient->name }}
                        </td>
                        <td>
                            {{ $appointment->patient->phone }}
                        </td>
                        <td>
                            {{ $appointment->type }}
                        </td>


                        <td>
                            <strong>{{ $appointment->date }} </strong>
                        </td>
                        <td>
                            {{ $appointment->payment_method }}
                        </td>
                        <td>
                            <strong class="text-{{ $appointment->paid ? 'success' : 'danger' }}">
                                {{ $appointment->paid ? 'Yes' : 'No' }}</strong>
                        </td>
                        <td>
                            <strong class="text-{{ $appointment->status == 'done' ? 'success' : 'danger' }}">
                                {{ $appointment->status }}</strong>
                        </td>
                        <td>
                            <a href="{{ route('reception.appointment.show', $appointment->id) }}"
                                class="btn btn-primary">show</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    @else
        <h6 class="text-secondary text-center my-4">No Appointments !</h6>
    @endif

@endsection
