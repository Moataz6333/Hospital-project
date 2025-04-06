@extends('layouts.recip')
@section('title')
    Transactions
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href="{{ route('clinic.show', $doctor->clinic_id) }}" class="btn btn-dark"><span
                    data-feather="arrow-left"></span></a>
            Transactions for dr. {{ $doctor->user->name }}
        </h2>
    </div>
    <hr>
    @if (session('success'))
        <p class="my-3 text-center text-success">
            {{ session('success') }}
        </p>
    @endif

    @if (count($appointments) != 0)
        <table class="table table-striped my-4">
            <thead class="table-dark">
                <tr>
                    <th>Invoice</th>
                    <th>
                        Name
                    </th>
                    <th>InvoiceValue</th>
                    <th>Date</th>
                    <th>Payment Gateway</th>
                    <th>Card Number</th>
                    <th>Paid</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $appointment)
                    @if ($appointment->transaction)
                        <tr>
                            <th scope="row">
                                {{ $appointment->transaction->InvoiceId }}
                            </th>
                            <td>
                                {{ $appointment->transaction->CustomerName }}
                            </td>
                            <td>
                                {{ $appointment->transaction->InvoiceValue . ' ' . $appointment->transaction->Currency }}
                            </td>
                            <td>
                                <strong>{{ date_create($appointment->transaction->created_at)->format('d-m-Y h:i a') }}</strong>
                            </td>
                            <td>
                                {{ $appointment->transaction->PaymentGateway }}
                            </td>


                            <td>
                                <strong>{{ $appointment->transaction->CardNumber }} </strong>
                            </td>

                            <td>
                                <strong class="text-{{ $appointment->paid ? 'success' : 'danger' }}">
                                    {{ $appointment->paid ? 'Yes' : 'No' }}</strong>
                            </td>
                            <td>
                                <form action="{{ route('transaction.delete', $appointment->transaction->id) }}"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>

                        </tr>
                    @endif
                @endforeach

            </tbody>
        </table>
    @else
        <h6 class="text-secondary text-center my-4">No Appointments !</h6>
    @endif

@endsection
