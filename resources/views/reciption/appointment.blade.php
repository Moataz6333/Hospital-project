@extends('layouts.recip')
@section('title')
    Appointment
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href="@if ($from == 'archive') {{ route('reception.archive', ['id' => $appointment->doctor->id, 'day' => GetCurrentDayForDoctor($appointment->doctor)]) }}  @else
        {{ route('reception.appointments', ['id' => $appointment->doctor->id, 'day' => GetCurrentDayForDoctor($appointment->doctor)]) }} @endif 
        "
                class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            Appointment for <u>{{ $appointment->patien->name }}</u>
            @if ($appointment->transaction)
                <a target="_blank" href="{{ route('hospital.sheet', $appointment->transaction->PaymentId) }}"
                    class="btn btn-info ml-3">Invoice</a>
            @endif
            @if (!$appointment->paid && $appointment->payment_method == 'online')
                <a href="{{env('APP_URL')}}/myfatoorah/checkout?oid={{$appointment->id}}" class="btn btn-success">Pay Online</a>
            @endif
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
        <form action="{{ route('appointment.update', $appointment->id) }}" method="post">
            @csrf
            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Name</label>
                    @error('name')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="name" class="form-control" required placeholder="Patien Name..."
                        value="{{ $appointment->patien->name }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    @error('phone')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="phone" placeholder="Patien phone ..."
                        value="{{ $appointment->patien->phone }}">
                </div>

            </div>
            <div class="row my-2">
                <div class="col">
                    <label for="" class="form-label">Appointment Day</label>
                    @error('day')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="day" required>
                        <option value="" selected disabled>Choose a day</option>
                        @foreach ($days as $key => $day)
                            <option value="{{ $day }}" @if (date_create($appointment->date)->format('l') == $day) selected @endif>
                                {{ $day }} from {{ $times[$key . '_start'] }} To {{ $times[$key . '_end'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="date" class="form-label">Appointment Date</label>
                    @error('date')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="date" class="form-control" name="date" required value="{{ $appointment->date }}" />
                </div>


            </div>
            <div class="row my-2">
                <div class="col">
                    <label for="type" class="form-label">Appointment Type</label>
                    @error('type')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="type" required>
                        <option value="examination" @if ($appointment->type == 'examination') selected @endif>examination - كشف
                        </option>
                        <option value="consultation" @if ($appointment->type == 'consultation') selected @endif>consultation -
                            استشاره</option>

                    </select>
                </div>
                <div class="col">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    @error('payment_method')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="payment_method" required>
                        <option value="cash" @if ($appointment->payment_method == 'cash') selected @endif>cash</option>
                        <option value="online" @if ($appointment->payment_method == 'online') selected @endif>online</option>

                    </select>
                </div>
                <div class="col align-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="paid"
                            @if ($appointment->paid) checked @endif>
                        <label class="form-check-label" for="flexCheckDefault">
                            @php
                                $currency = config('app.currency');
                            @endphp
                            Paid {{ $appointment->doctor->price }} {{ $currency }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex w-100 justify-content-end">
                <button class="btn btn-primary " type="submit">Update</button>
            </div>
        </form>

        <div class="my-2 ">
            <form action="{{ route('appointment.destroy', $appointment->id) }}" method="POST"
                onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>

        </div>
    </div>
@endsection
