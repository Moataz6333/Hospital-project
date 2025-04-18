@extends('layouts.recip')
@section('title')
    Donation
@endsection
@section('content')
    <div class="d-flex gap-2 my-3 align-items-center">
        <h2>
            <a href=" {{ route('donations.index') }} " class="btn btn-dark"><span data-feather="arrow-left"></span>
           </a><h3> Donation {{$donation->id}}</h3>

            @if ($donation->transaction)
                <a target="_blank" href="{{route('donation.sheet',$donation->transaction->PaymentId)}}" class="btn btn-info ml-3">Invoice</a>
            @endif
            @if (!$donation->paid && $donation->payment_method == 'online')
                <a href="{{ env('APP_URL') }}/myfatoorah/checkout?did={{ $donation->id }}" class="btn btn-success">Pay
                    Online</a>
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
        <form action="{{ route('donations.update', $donation->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Name</label>
                    @error('name')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="name" class="form-control" required placeholder="patient Name..."
                        value="{{ $donation->patient->name }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    @error('phone')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="phone" placeholder="patient phone ..."
                        value="{{ $donation->patient->phone }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">National id</label>
                    @error('national_id')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="national_id" placeholder="National id  ..."
                        value="{{ $donation->patient->national_id }}">
                </div>

            </div>
            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Donation Value</label>
                    @error('value')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="value" class="form-control" required placeholder="Value ..."
                        value="{{ $donation->value }}">
                </div>
                <div class="col">
                    <label for="name" class="form-label"> Currency</label>
                    @error('currency')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="currency" required>
                        <option value="EGP" @if($donation->currency =='EGP') selected @endif>EPG</option>
                        <option value="KWD" @if($donation->currency =='KWD') selected @endif>KWD</option>

                    </select>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-2">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    @error('payment_method')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="payment_method" required>
                        <option value="" selected disabled>Choose a Type</option>
                        <option value="cash" @if($donation->payment_method =='cash') selected @endif>cash</option>
                        <option value="online" @if($donation->payment_method =='online') selected @endif>online</option>

                    </select>
                </div>
                <div class="col align-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="paid"
                            @if ($donation->paid) checked @endif>
                        <label class="form-check-label" for="flexCheckDefault">
                           
                            Paid {{ $donation->value }} {{ $donation->currency }}
                        </label>
                    </div>
                </div>
            
            </div>

            <div class="mt-3 d-flex w-100 justify-content-end">
                <button class="btn btn-primary " type="submit">Update</button>
            </div>
        </form>

        <div class="my-2 ">
            <form action="{{ route('donations.destroy', $donation->id) }}" method="POST" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>

        </div>
    </div>
@endsection
