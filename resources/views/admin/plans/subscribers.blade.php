@extends('layouts.main')
@section('title')
    Plans
@endsection
@section('content')
    <div class="d-flex gap-3 flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <a href="{{ route('plans.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
        <h1 class="h2">Subscribers of Plan <u>{{ $plan->title }}</u></h1>
    </div>
    @include('inc.alerts')

    <table class="table table-striped my-4">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>
                    Name
                </th>
                <th>Phone</th>
                <th>National_id</th>
                <th>Payment Method</th>
                <th>Registration Method</th>
                <th>Paid</th>
                <th>Created</th>
                <th>Ends</th>
                <th>Print</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @foreach ($plan->subscribers as $subscriber)
                <tr>
                    <th scope="row" class="align-content-center">
                        {{ $subscriber->id }}
                    </th>
                    <td>
                        {{ $subscriber->patient->name }}
                    </td>
                    <td>
                        {{ $subscriber->patient->phone }}
                    </td>
                    <td>
                        {{ $subscriber->patient->national_id }}
                    </td>

                    <td>
                        {{ $subscriber->payment_method }}
                    </td>
                    <td>
                        {{ $subscriber->registeration_method }}
                    </td>
                    <td>
                        <strong class="text-{{ $subscriber->paid ? 'success' : 'danger' }}">
                            {{ $subscriber->paid ? 'Yes' : 'No' }}</strong>
                    </td>
                    <td>
                        <strong>
                            {{ date_create($subscriber->created_at)->format('d-m-Y') }}</strong>
                    </td>
                    <td>
                        <strong>
                            {{ date_create($subscriber->subscription_date)->format('d-m-Y') }}</strong>
                    </td>
                   
                    <td>
                        <a href="{{route('subscriber.sheet.export',$subscriber->subscribtion_id)}}" class="btn btn-success"><span data-feather="hard-drive"></span></a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
