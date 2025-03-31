@extends('layouts.doctor')
@section('title')
    Archive
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href="{{ route('doctor.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            Archive
        </h2>
    </div>
    {{-- days --}}
    <div class="d-flex justify-content-evenly w-100 my-3">
        @foreach ($days as $key => $val)
            <a href="{{ route('doctor.archive', [ 'day' => $val]) }}"
                class="btn @if ($val == $current) btn-secondary @else btn-dark @endif">{{ $val }} </a>
        @endforeach
    </div>
    {{-- appointments --}}
    @if (count($appointments) != 0)
        <table class="table  my-4">
            <thead class="table-secondary">
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
                            <a href="{{ route('doctor.appointment.show', ['id' => $appointment->id, 'from' => 'archive']) }}"
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
