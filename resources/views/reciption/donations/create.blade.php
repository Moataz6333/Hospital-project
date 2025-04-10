@extends('layouts.recip')
@section('title')
    Donations
@endsection
@section('content')
    <h2 class="my-2">
        <a href="{{ route('donations.index') }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
        Donation
    </h2>
    <hr>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif
    <div class="my-3">
        <form action="{{route('donations.store')}}" method="post">
            @csrf
            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Name</label>
                    @error('name')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="name" class="form-control" required placeholder="Customer Name ..."
                        value="{{ old('name') }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    @error('phone')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="phone" placeholder="Customer phone ..."
                        value="{{ old('phone') }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">National id</label>
                    @error('national_id')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="national_id" placeholder="National id  ..."
                        value="{{ old('national_id') }}">
                </div>

            </div>

            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Donation Value</label>
                    @error('value')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="value" class="form-control" required placeholder="Value ..."
                        value="{{ old('value') }}">
                </div>
                <div class="col">
                    <label for="name" class="form-label"> Currency</label>
                    @error('currency')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="currency" required>
                        <option value="EGP" selected>EPG</option>
                        <option value="KWD">KWD</option>

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
                        <option value="cash">cash</option>
                        <option value="online">online</option>

                    </select>
                </div>
                <div class="col align-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="paid">
                        <label class="form-check-label" for="flexCheckDefault">
                           
                            Paid 
                        </label>
                    </div>
                </div>
            </div>
           
            <div class="mt-3 d-flex w-100 justify-content-end">
                <button class="btn btn-success " type="submit">Donate</button>
            </div>
        </form>
    </div>
@endsection
