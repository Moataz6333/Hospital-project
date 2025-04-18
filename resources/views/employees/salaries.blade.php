@extends('layouts.main')
@section('title')
    Employees
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Salaries</h1>
    </div>
    @if (session('success'))
        <div class="w-100 my-2">
            <p class="text-center text-success">
                {{ session('success') }}
            </p>
        </div>
    @endif
    {{-- total --}}
    <div class="header border border-info rounded p-3">
        <h2>
            The Total number of Salaries : <u> {{ $total }}</u> {{ config('app.currency') }}
        </h2>
    </div>



    {{-- Employees salary --}}
    <div class="border  rounded my-3 ">
        <h2 class="m-3 mt-4">Employees Roles
            @can('isSuperAdmin')
                <a href="{{ route('roles.index') }}" class="btn btn-primary">edit</a>
            @endcan
        </h2>
        <div class="d-flex justify-content-evenly p-3">
            @foreach ($counts as $key => $val)
                <div class="p-2 border border-primary rounded text-center">
                    <h5>{{ $val['count'] }} {{ $key }}s</h5>
                    <b>salary: </b> {{ $val['default'] }} <br>
                    <b>incentives:</b> {{ $val['incentives'] }} <br><br>
                    <strong>Total: {{ $val['total'] }} {{ config('app.currency') }} </strong>
                </div>
            @endforeach
        </div>
    </div>
    {{-- Doctors --}}
    <div class="border border-primary my-3 p-3 rounded">
        <h2>Doctors Salaries <a href="{{route('doctors.index')}}" class="btn btn-success">show</a></h2>

        <h4>  The total number of Doctors : {{$doctors}} </h4>
        <h4>  The total Salaries: <u>{{$doctors_salary}} {{config('app.currency')}} </u></h4>

    </div>
@endsection
