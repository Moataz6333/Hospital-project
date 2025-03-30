@extends('layouts.recip')
@section('title')
    Registration
@endsection
@section('content')
    <h2 class="my-2">
        <a href="{{ route('clinic.show', $doctor->clinic_id) }}" class="btn btn-dark"><span
                data-feather="arrow-left"></span></a>
        Dr. <u>{{ $doctor->user->name }} </u> Registration
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
        <form action="{{route('appointment.create',$doctor->id)}}" method="post">
            @csrf
            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Name</label>
                    @error('name')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="name" class="form-control" required placeholder="Patien FName LName ." value="{{ old('name') }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    @error('phone')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="phone" placeholder="Patien phone ..." value="{{ old('phone') }}">
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
                        
                        <option value="{{$day}}">{{$day}} from {{$times[$key.'_start']}} To {{$times[$key.'_end']}} </option>
                            
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="date" class="form-label">Appointment Date</label>
                    @error('date')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="date" class="form-control" name="date" required />
                </div>
                
               
            </div>
            <div class="row my-2">
                 <div class="col">
                    <label for="type" class="form-label">Appointment Type</label>
                    @error('type')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="type" required>
                        <option value="" selected disabled>Choose a Type</option>
                        <option value="examination"  >examination - كشف</option>
                        <option value="consultation"  >consultation - استشاره</option>
                      
                    </select>
                </div>
                <div class="col">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    @error('payment_method')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select class="form-select" name="payment_method" required>
                        <option value="" selected disabled>Choose a Type</option>
                        <option value="cash"  >cash</option>
                        <option value="online"  >online</option>
                      
                    </select>
                </div>
                <div class="col align-content-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="paid">
                        <label class="form-check-label" for="flexCheckDefault">
                            @php
                                $currency =config('app.currency');
                            @endphp
                          Paid {{$doctor->price }} {{$currency}}
                        </label>
                      </div>
                </div>
            </div>
            <div class="mt-3 d-flex w-100 justify-content-end">
                <button class="btn btn-primary " type="submit">Create</button>
            </div>
        </form>
    </div>
@endsection
