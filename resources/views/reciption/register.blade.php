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
    @include('inc.alerts')
    <div class="my-3">
        <form action="{{ route('appointment.create', $doctor->id) }}" method="post">
            @csrf
            <div class="row my-2">
                <div class="col">
                    <label for="name" class="form-label"> Name</label>
                    @error('name')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="name" class="form-control" required placeholder="patient FName LName ."
                        value="{{ old('name') }}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone</label>
                    @error('phone')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="phone" placeholder="patient phone ..."
                        value="{{ old('phone') }}">
                </div>
                <div class="col">
                    <label for="national_id" class="form-label">National id</label>
                   
                    @error('national_id')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" class="form-control" name="national_id" placeholder="Nationl id (op) ..."
                        id="searchInput" value="{{ old('national_id') }}">
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
                        value="{{ old('age') }}">
                </div>
                <div class="col d-flex gap-5 align-items-center">
                    <label for="gender" class="form-label">Gender</label>
                    @error('gender')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="male">
                        <label class="form-check-label" for="gender">
                            Male
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="female">
                        <label class="form-check-label" for="gender">
                            Female
                        </label>
                    </div>

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
                            <option value="{{ $day }}">{{ $day }} from {{ $times[$key . '_start'] }} To
                                {{ $times[$key . '_end'] }} </option>
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
                        <option value="examination">examination - كشف</option>
                        <option value="consultation">consultation - استشاره</option>

                    </select>
                </div>
                <div class="col">
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
                            @php
                                $currency = config('app.currency');
                            @endphp
                        <label class="form-check-label" for="flexCheckDefault" id="price">
                            Paid {{ $doctor->price }} {{ $currency }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex w-100 justify-content-end">
                <button class="btn btn-primary " type="submit">Create</button>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
           
            $('#searchInput').on('keyup', function() {
                let query = $(this).val();

                if (query.length > 0) {
                    $.ajax({
                        url: "{{ route('patient.discount') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            national_id: query,
                            doctor_id: "{{ $doctor->id }}"
                        },
                        success: function(response) {

                            if (response.status !== 'not-found') {
                                $('#discount-span').text(response.message);
                                $('#price').text(response.price+" {{$currency}}")
                            }else{
                                $('#discount-span').empty();
                                $('#price').text("{{$doctor->price}} {{$currency}}")
                            }

                        },

                    });
                }
            });
        });
    </script>
@endsection
