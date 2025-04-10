@extends('layouts.recip')
@section('title')
    Donations
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href="{{ route('reception.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            Donations
        </h2>
    </div>
    <hr>
   
    @if (count($donations) != 0)
        <table class="table table-striped my-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>
                        Name
                    </th>
                    <th>Phone</th>
                    <th>National_id</th>
                    <th>Value</th>
                    <th>Payment Method</th>
                    <th>Registration Method</th>
                    <th>Date</th>
                    <th>Paid</th>
                    <th>Show</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($donations as $donation)
                    <tr>
                        <th scope="row" class="align-content-center">
                           {{$donation->id}}
                        </th>
                        <td>
                            {{ $donation->name }}
                        </td>
                        <td>
                            {{ $donation->phone }}
                        </td>
                        <td>
                            {{ $donation->national_id }}
                        </td>


                        <td>
                            <strong>{{ $donation->value .' '. $donation->currency }} </strong>
                        </td>
                        <td>
                            {{ $donation->payment_method }}
                        </td>
                        <td>
                            {{ $donation->registeration_method }}
                        </td>
                        <td>
                            <strong class="text-{{ $donation->paid ? 'success' : 'danger' }}">
                                {{ $donation->paid ? 'Yes' : 'No' }}</strong>
                        </td>
                        <td>
                            <strong >
                                {{ date_create($donation->created_at)->format("d-m-Y h:i a") }}</strong>
                        </td>
                        <td>
                            <a href="{{ route('donations.show', $donation->id) }}"
                                class="btn btn-primary">show</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    @else
        <h6 class="text-secondary text-center my-4">No donations !</h6>
    @endif

@endsection
