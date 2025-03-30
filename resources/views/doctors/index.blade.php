@extends('layouts.doctor')
@section('title')
    Doctor
@endsection
@section('content')

    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            Appointments
        </h2>

        <p class="text-muted m-0">{{ $week . ' ' . $current . ' ' . $date }} </p>
    </div>
    <hr>
    {{-- days --}}
    <div class="d-flex justify-content-evenly w-100 my-3">
        @foreach ($days as $key => $val)
            <a href="{{ route('doctor.index', ['day' => $val]) }}"
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
            <tbody  id="appointmentsTable">
                @php
                    $i = 1;
                @endphp
                @foreach ($appointments as $appointment)
                    <tr>
                        <th scope="row">
                            {{ $i++ }}
                        </th>
                        <td>
                            {{ $appointment->patien->name }}
                        </td>
                        <td>
                            {{ $appointment->patien->phone }}
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
                            <a href="{{ route('doctor.appointment.show', $appointment->id) }}"
                                class="btn btn-primary">show</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    @else
        <h6 class="text-secondary text-center my-4">No Appointments !</h6>
    @endif


    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            Pusher.logToConsole = false;
            var doctorId = {{ auth()->user()->id }};

            window.Echo.private(`private-doctor.${doctorId}`)
                .listen(".new-appointment", (data) => {
                    // console.log("New Appointment Received:", data.appointment);
                    let appointment = data.appointment;
                    let patient = appointment.patien;

                    // Format the status and paid fields
                    let paidClass = appointment.paid ? "text-success" : "text-danger";
                    let paidText = appointment.paid ? "Yes" : "No";
                    let statusClass = appointment.status == "done" ? "text-success" : "text-danger";

                    // Get table body
                    let tbody = document.querySelector("table tbody");

                    // Get the last row index and increase it
                    let lastIndex = tbody.rows.length + 1;

                    // Create a new row and insert data
                    let newRow = `<tr>
            <th scope="row">${lastIndex}</th>
            <td>${patient.name}</td>
            <td>${patient.phone}</td>
            <td>${appointment.type}</td>
            <td><strong>${appointment.date}</strong></td>
            <td>${appointment.payment_method}</td>
            <td><strong class="${paidClass}">${paidText}</strong></td>
            <td><strong class="${statusClass}">${appointment.status}</strong></td>
            <td>
                <a href="/doctor/appointment/${appointment.id}" class="btn btn-primary">Show</a>
            </td>
        </tr>`;

                    // Append the new row
                    tbody.innerHTML += newRow;

                })
                .error(error => {
                    console.error("Subscription Error:", error);
                });
        });
    </script>


@endsection
