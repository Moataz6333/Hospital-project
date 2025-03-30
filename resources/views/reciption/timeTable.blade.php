@extends('layouts.recip')
@section('title')
    Reciptionist
@endsection
@section('content')
    <div class="my-3">
        <h2 class="h2">
            <a href="{{ route('clinic.show', $timeTable->doctor->clinic_id) }}" class="btn btn-dark"><span
                    data-feather="arrow-left"></span></a>
            time table of dr. <u>{{ $timeTable->doctor->user->name }}</u>
        </h2>
        <span class="text-muted">{{ $timeTable->doctor->name }} </span>
        <hr>
    </div>
    @php
        $days = [
            'sun' => 'Sunday',
            'mon' => 'Monday',
            'tue' => 'tuesday',
            'wed' => 'Wednessday',
            'thurs' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
        ];

    @endphp
    <table class="table table-striped my-3 ">
        <thead class="table-dark">
            <tr>
                <th scope="col">
                    Day
                </th>
                <th>From</th>
                <th>To</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($days as $key => $day)
                <tr>
                    <th scope="row">{{ $day }} </th>
                    <td>
                        @if ($timeTable[$key . '_start'] != null)
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $timeTable[$key . '_start'])->format('h:i A') }}
                        @else
                            OFF
                        @endif
                    </td>
                    <td>
                        @if ($timeTable[$key . '_end'] != null)
                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $timeTable[$key . '_end'])->format('h:i A') }}
                        @else
                            OFF
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
