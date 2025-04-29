@extends('layouts.main')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2">Dashboard</h1>

    </div>
    {{-- balance pie --}}
    <div class="row d-flex w-100 justify-content-evenly align-content-cener border-bottom rounded ">
        <div class="col-6 d-flex justify-content-center flex-column gap-3">
            <h2>-The Total Balance : <u id="total"><strong>{{ $balance['total'] }}</strong></u>
                {{ config('app.currency') }} </h2>
            <ul>
                <li>
                    <h3>Cash : <span id="cash" class="text-secondary">{{ $balance['cash'] }}</span>
                        {{ config('app.currency') }}</h3>
                </li>
                <li>
                    <h3>Transactions : <span id="transactions" class="text-secondary">{{ $balance['transactions'] }}</span>
                        {{ config('app.currency') }}</h3>
                </li>
                <li>
                    <h3>Donations : <span id="donations" class="text-secondary">{{ $balance['donations'] }}</span>
                        {{ config('app.currency') }}</h3>
                </li>
                <li>
                    <h3>Subscribers : <span id="subscribers" class="text-secondary">{{ $balance['subscribers'] }}</span>
                        {{ config('app.currency') }}</h3>
                </li>
            </ul>
        </div>
        <div class="col-3">
            <canvas class="my-4 w-100" id="myChart" width="380" height="380"></canvas>

        </div>
    </div>
    {{-- transactions --}}
    <div class="my-4">
        <div class="d-flex gap-4 my-2">
            <h2>- Transactions</h2>
            <a href="{{ route('transactions.index') }}" class="btn btn-dark align-content-center">show</a>
        </div>
        <p class="text-muted">Last 5 transactions</p>
        <div class="table-responsive">
            <table class="table table-striped table-sm table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Invoice</th>
                        <th>Name</th>
                        <th>InvoiceValue</th>
                        <th>Date</th>
                        <th>Payment Gateway</th>
                        <th>Card Number</th>
                        <th>Service</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <th scope="row">
                                {{ $transaction->InvoiceId }}
                            </th>
                            <td>
                                {{ $transaction->CustomerName }}
                            </td>
                            <td>
                                {{ $transaction->InvoiceValue . ' ' . $transaction->Currency }}
                            </td>
                            <td>
                                <strong>{{ date_create($transaction->created_at)->format('d-m-Y h:i a') }}</strong>
                            </td>
                            <td>
                                {{ $transaction->PaymentGateway }}
                            </td>


                            <td>
                                <strong>{{ $transaction->CardNumber }} </strong>
                            </td>


                            <td>
                                {{ $transaction->service }}

                            </td>
                            <td>
                                @if ($transaction->donation)
                                  <b>  <span class="text-success">donation</span> </b>
                                @else
                                    @if ($transaction->subscriber)
                                    <b> <span class="text-primary">subscriber</span>  </b>   
                                    @else
                                    <b>  <span class="text-dark">appointment</span>   </b>
                                    @endif
                                    
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- week data --}}
    <div class="d-flex my-2 align-items-end ">
        <div class="d-flex align-items-end gap-4">
            <h2>-Week Outcomes Chart</h2>
            <!-- weeks dropDown-->
            <div class="btn-group">
                <button type="button" id="weekDropDown" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    {{ $currentWeek->format('d-M-Y') }}
                </button>
                <ul class="dropdown-menu" id="weeks">
                    @foreach ($weeks as $week)
                        <li style="cursor: pointer;"><a class="dropdown-item">{{ $week }} </a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="w-100 row justify-content-center my-4">
        <div class="col-5">
            <canvas class="my-4 w-100 chartjs-render-monitor" id="weekChart" width="380" height="380"></canvas>
        </div>
        <div class="col align-content-center">
            <h3>Summary Statistics</h3>
            <ul>
                <li>
                    <h4>Appointments : <span class="text-primary" id="weekCount">{{ $weekData['count'] }} </span></h4>
                </li>
                <li>
                    <h4>Average : <span class="text-primary" id="weekAve">{{ $weekData['ave'] }}</span></h4>
                </li>
                <li>
                    <h4>Max : <span class="text-primary" id="weekMax">{{ $weekData['max'] }}</span></h4>
                </li>
                <li>
                    <h4>min : <span class="text-primary" id="weekMin">{{ $weekData['min'] }}</span></h4>
                </li>
                <li>
                    <h4>Total outcome : <span class="text-primary" id="weekTotal">{{ $weekData['total'] }}</span></h4>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    {{-- month chart --}}
    <div class="my-4">
        <div class="d-flex gap-3 align-items-end">
            <h2 class="my-2">- Month Chart</h2>
            <p class="text-muted">from <span class="text-primary" id="from"></span> to <span class="text-primary"
                    id="to"></span></p>
        </div>
        <div class="row">
            <div class="col">
                <canvas id="monthChart" class="my-4 w-100" width="900" height="380"></canvas>
            </div>
        </div>
    </div>
    {{-- months data --}}
    @foreach ($months as $key => $val)
        <input type="hidden" name="{{ $key }}" value="{{ $val }}" class="monthsData">
    @endforeach












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
        let sunday = {{ $weekData['days']['sunday'] }};
        let monday = {{ $weekData['days']['monday'] }};
        let tuesday = {{ $weekData['days']['tuesday'] }};
        let wednesday = {{ $weekData['days']['wednesday'] }};
        let thursday = {{ $weekData['days']['thursday'] }};

        var ctx = document.getElementById('myChart');
        var pie = [+document.getElementById('cash').textContent, +document.getElementById('transactions').textContent, +
            document.getElementById('donations').textContent,
            + document.getElementById('subscribers').textContent
        ];
        const pieData = {
            labels: [
                'Cash',
                'Transaction',
                'Donation',
                'subscribers'
            ],
            datasets: [{
                label: 'sum',
                data: pie,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    '#27960e'
                ],
                hoverOffset: 4
            }]
        };
        // eslint-disable-next-line no-unused-vars
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: pieData
        });
        // month chart
        var ctx3 = document.getElementById('monthChart');
        let months_labels = [];
        let months_amounts = [];
        document.querySelectorAll(".monthsData").forEach(element => {
            months_labels.push(element.name);
            months_amounts.push(+element.value);
        });
        $("#from").text(months_labels[0]);
        $("#to").text(months_labels[months_labels.length - 1]);

        var monthChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: months_labels,
                datasets: [{
                    data: months_amounts,
                    label: "in {{ config('app.currency') }}",
                    lineTension: 0,
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    borderWidth: 4,
                    pointBackgroundColor: '#007bff'
                }]
            },
            options: {
                scales: {
                  
                },
                legend: {
                    display: false
                }
            }
        });
        var ctx2 = document.getElementById('weekChart')
        // eslint-disable-next-line no-unused-vars
        var weekChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: [
                    'Sunday',
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                ],
                datasets: [{
                    data: [
                        sunday,
                        monday,
                        tuesday,
                        wednesday,
                        thursday
                    ],
                    label: "in {{ config('app.currency') }}",
                    borderColor: '#007bff',
                    borderWidth: 3,
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        });
        // weeks ul
        $(".dropdown-item").click(function(e) {
            e.preventDefault();
            date = $(this).text();
            $("#weekDropDown").text(date);
            $.ajax({
                url: `weekData/${date}`,
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    sunday = response.days.sunday;
                    monday = response.days.monday;
                    tuesday = response.days.tuesday;
                    wednesday = response.days.wednesday;
                    thursday = response.days.thursday;
                    $("#weekCount").text(response.count);
                    $("#weekAve").text(response.ave);
                    $("#weekMax").text(response.max);
                    $("#weekMin").text(response.min);
                    $("#weekTotal").text(response.total);
                    weekChart.destroy();
                    weekChart = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: [
                                'Sunday',
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                            ],
                            datasets: [{
                                data: [
                                    sunday,
                                    monday,
                                    tuesday,
                                    wednesday,
                                    thursday
                                ],
                                label: "in {{ config('app.currency') }}",
                                borderColor: '#007bff',
                                borderWidth: 3,
                            }]
                        },
                        options: {
                            legend: {
                                display: false
                            }
                        }
                    });
                }
            });


        });
    </script>
@endsection
