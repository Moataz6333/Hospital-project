@extends('layouts.main')
@section('title')
    Plans
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2">Plans</h1>
    </div>
    @include('inc.alerts')
    <div class="plans d-flex flex-wrap gap-3">
        @foreach ($plans as $plan)
        <div class="card" style="width: 21rem;">
            <div class="card-header d-flex justify-content-between">
                <h6 class="h6">Plan {{$plan->id}}</h6>
                <p class="text-muted"> {{Carbon\Carbon::create($plan->created_at)->diffForHumans()}} </p>
            </div>
            <div class="card-body">
                <h5 class="card-title"> {{$plan->title}}</h5>
                <div class="row my-2">
                    <div class="col-8 align-items-center">
                        <p class="card-text">
                            <ul>
                                @foreach (explode("\r\n",$plan->features) as $feat)
                                    <li><span data-feather="check" class="text-success"></span> {{$feat}} </li>
                                @endforeach
                            </ul>
                        </p>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <i class="{{$plan->icon}}"></i>
                    </div>
                </div>
                
            </div>
            <div class="card-footer">
                <div class="d-flex w-100 justify-content-between align-items-end">
                     <a href="{{route('subscribers.index',$plan->id)}}" class="btn btn-success">subscribers({{count($plan->subscribers)}})</a>
                     <a href="{{route('plans.show',$plan->id)}}" class="btn btn-primary justify-self-end">show</a>
                
                    </div>
            </div>
        </div>
        @endforeach

    </div>
@endsection
