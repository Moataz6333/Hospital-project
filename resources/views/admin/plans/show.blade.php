@extends('layouts.main')
@section('title')
    Plans
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <div class="d-flex gap-3">
            <a href="{{route('plans.index')}}" class="btn btn-dark" ><span data-feather="arrow-left"></span></a>   <h1 class="h2">Plan {{$plan->id}} </h1>
        </div>
        <div class=" d-flex">
            <form action="{{route('plans.destroy',$plan->id)}}" method="post" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
        </div>
    </div>
    @include('inc.alerts')
    <div class="my-3">
        <form action="{{ route('plans.update',$plan->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col my-3">
                    <label for="title" class="form-label">Title</label>
                    @error('title')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" name="title" value="{{$plan->title}}" required placeholder="plan name" class="form-control">
                </div>
                <div class="col my-3">
                    <label for="period" class="form-label">Period</label>
                    @error('period')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <select name="period" id="period" class="form-select">
                        <option value="month" @if($plan->period==30) selected @endif>Monthly</option>
                        <option value="year" @if($plan->period==365) selected @endif>Yearly</option>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="col my-3">
                    <label for="price" class="form-label">Price</label>
                    @error('price')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" required name="price" value="{{$plan->price}}" placeholder="in {{ config('app.currency') }}"
                        class="form-control">
                </div>

                <div class="col my-3">
                    <label for="icon" class="form-label">Icon</label>
                    @error('icon')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <input type="text" required name="icon" value="{{$plan->icon}}" placeholder="icon class" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="features" class="form-label">Features</label>
                    @error('features')
                        <p class="text-danger "><small>{{ $message }}</small> </p>
                    @enderror
                    <textarea class="form-control" name="features" id="features" cols="30" rows="4" placeholder="feat1
feat2
...">{{$plan->features}} </textarea>
                </div>
            </div>
            <div class="d-flex w-100 justify-content-end my-3">
                <button class="btn btn-primary" type="submit">update</button>
            </div>
        </form>
       
    </div>
@endsection
