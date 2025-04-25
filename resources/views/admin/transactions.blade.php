@extends('layouts.main')
@section('title')
    Transactions
@endsection
@section('content')
    <div class="d-flex gap-3 flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
      <a href="{{route('dashboard')}}" class="btn btn-dark"><span
        data-feather="arrow-left"></span></a>  <h1 class="h2">Transactions</h1>

    </div>
    <div  class="table-responsive">
        <table class="table table-striped table-sm table-hover">
            <thead class="table-dark">
              <tr>
                <th>Invoice</th>
                <th>Name</th>
                <th>InvoiceValue</th>
                <th>Date</th>
                <th>Payment Gateway</th>
                <th>Card Number</th>
                <th>Service</th>
                <th>Type</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr>
                    <th scope="row">
                        {{ $transaction->InvoiceId }}
                    </th>
                    <td>
                        {{ $transaction->CustomerName }}
                    </td>
                    <td>
                        {{ $transaction->InvoiceValue . ' ' . $transaction->Currency }}
                    </td>
                    <td>
                        <strong>{{ date_create($transaction->created_at)->format('d-m-Y h:i a') }}</strong>
                    </td>
                    <td>
                        {{ $transaction->PaymentGateway }}
                    </td>


                    <td>
                        <strong>{{ $transaction->CardNumber }} </strong>
                    </td>

                    
                    <td>
                        {{ $transaction->service }}
                        
                    </td>
                    <td>
                        @if ($transaction->donation)
                            donation
                        @else
                            appointment
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
