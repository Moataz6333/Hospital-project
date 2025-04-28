@extends('layouts.recip')
@section('title')
    Plans
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div class="d-flex gap-3">
            <a href="{{ route('reception.subscribers',$subscriber->plan_id) }}" class="btn btn-dark"><span data-feather="arrow-left"></span></a>
            <h1 class="h2">edit subscriber <u>{{$subscriber->patient->name}} </u></h1>
        </div>
        <form action="{{route('reception.subscriber.destroy',$subscriber->id)}}" method="post" onsubmit="return confirmDelete()">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit"><span data-feather="trash-2"></span></button>
        </form>
    </div>
    @include('inc.alerts')
    <div class="my-3">
        <form action="{{route('reception.subscriber.update',$subscriber->id)}}" method="post" >
            @csrf
            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Name</label>
                    @error('name')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="name" class="form-control" required placeholder="patient FName LName ."
                        value="{{ $subscriber->patient->name }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    @error('phone')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="phone" placeholder="patient phone ..."
                        value="{{ $subscriber->patient->phone }}">
                </div>
                <div class="col">
                    <label for="national_id" class="form-label">National id</label>

                    @error('national_id')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="national_id" placeholder="Nationl id (op) ..."
                        id="searchInput" value="{{ $subscriber->patient->national_id }}">
                    <p class="text-success" id="discount-span"></p>
                </div>

            </div>
            <div class="row my-2 ">
                <div class="col ">
                    <label for="age" class="form-label"> Age</label>
                    @error('age')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="age" class="form-control" required placeholder="Age"
                        value="{{ $subscriber->patient->age  }}">
                </div>
                <div class="col d-flex gap-5 align-items-center">
                    <label for="gender" class="form-label">Gender</label>
                    @error('gender')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="male" @if($subscriber->patient->gender =='male') checked @endif>
                        <label class="form-check-label" for="gender">
                            Male
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="female" @if($subscriber->patient->gender =='female') checked @endif>
                        <label class="form-check-label" for="gender">
                            Female
                        </label>
                    </div>

                </div>

            </div>
            <div class="row my-2">
                <div class="col">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    @error('payment_method')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="payment_method" required>
                        <option value=""  disabled>Choose a Type</option>
                        <option value="cash" @if($subscriber->payment_method =='cash') selected @endif>cash</option>
                        <option value="online" @if($subscriber->payment_method =='online') selected @endif>online</option>
                    </select>
                </div>
                <div class="col align-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="paid" @if($subscriber->paid) checked @endif>
                        <label class="form-check-label" for="flexCheckDefault" id="price">
                            Paid {{ $subscriber->plan->price }} {{ config('app.currency') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex w-100 justify-content-end">
                <button class="btn btn-success " type="submit">Update</button>
            </div>
        </form>
    </div>
@endsection
