@extends('layouts.main')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>

    </div>
    <div class="row d-flex w-100 justify-content-evenly align-content-cener border-bottom rounded ">
        <div class="col-6 d-flex justify-content-center flex-column gap-3">
            <h2>The Total Balance : <u id="total"><strong>{{ $total }}</strong></u> {{ config('app.currency') }} </h2>
            <ul>
                <li>
                    <h3>Cash : <span class="text-secondary">3000 EGP</span></h3>
                </li>
                <li>
                    <h3>Transactions : <span class="text-secondary">3000 EGP</span></h3>
                </li>
                <li>
                    <h3>Donations : <span class="text-secondary">3000 EGP</span></h3>
                </li>
            </ul>
        </div>
        <div class="col-3">
            <canvas class="my-4 w-100" id="myChart" width="380" height="380"></canvas>

        </div>
    </div>













    {{-- pusher --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            Pusher.logToConsole = false;

            window.Echo.private('private-balanceUpdated')
                .listen(".new-balance", (data) => {
                    $.ajax({
                        url: "{{ route('total.json') }}",
                        method: "GET",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#total').text(response.total);
                        }
                    });

                })
                .error(error => {
                    console.error("Subscription Error:", error);
                });
        });
    </script>
    {{-- charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('myChart');
        const pieData = {
            labels: [
                'Cash',
                'Transaction',
                'Donation'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };
        // eslint-disable-next-line no-unused-vars
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: pieData
        });
    </script>
@endsection
