<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subscription</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px
        }

        .row {
            display: flex;
            /* justify-content: center; */
            padding: 4px 0;
            width: 100%;
            gap: 15px;
            margin: 8px;
        }

        hr {
            margin: 8px 0;
        }

        h1 {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" >
            <h1>Registration Invoice</h1>
        </div>

        <div class="row">
            <pre> <b>Patient Name:</b>   {{ $subscriber->patient->name }}          <b>Patient Phone:</b>  {{ $subscriber->patient->phone }}  </pre>
        </div>
        <div class="row">
            <pre> <b>National_id :</b>   {{ $subscriber->patient->national_id }}        <b>Patient Age:</b>  {{ $subscriber->patient->age }}  <b> gender:</b>  {{ $subscriber->patient->gender }}  </pre>
        </div>
        <div class="row">
            <pre> <b>Plan :</b>   {{ $subscriber->plan->title }}        <b>Plan period :</b>   {{ $subscriber->plan->period }} days   </pre>
        </div>

        <div class="row">
            <pre> <b>Date:</b> {{ date_create($subscriber->created_at)->format('l d-m-Y') }}        <b>End in:</b>  {{ date_create($subscriber->subscription_date)->format('l d-m-Y')  }} </pre>
        </div>

     

        <div class="row">
            <pre> <b>Plan Price:</b> {{ $subscriber->plan->price }} {{ config('app.currency') }}            <b>Paid:</b> @if ($subscriber->paid) Yes @else No @endif </pre>
        </div>

        <hr>

        <h1 style="text-align: center;">Plan Details</h1>
            <div class="row">
                <ul style="margin-left: 25px;">
                    @foreach (explode("\r\n",$subscriber->plan->features) as $feat)
                        <li>{{$feat}} </li>
                    @endforeach
                </ul>
            </div>
        <hr>

        <h1 style="text-align: center;">Payment Details</h1>

        <div class="row">
            <pre> <b>Payment Method:</b> {{ $subscriber->payment_method }}           <b>Registration:</b> {{ $subscriber->registeration_method }} </pre>
        </div>
        @if ($subscriber->transaction)
      
        <div class="row">
            <pre> <b>Invoice Id:</b> {{ $subscriber->transaction->InvoiceId }}        <b>Invoice Reference:</b> {{ $subscriber->transaction->InvoiceReference }} </pre>
        </div>

        <div class="row">
            <pre> <b>Invoice Value:</b> {{ $subscriber->transaction->InvoiceValue }}         <b>Currency:</b> {{ $subscriber->transaction->Currency }} (= {{ env('MYFATOORAH_EXCHANGE_RATE') }} EGP) </pre>
        </div>

        <div class="row">
            <pre> <b>Customer Name:</b> {{ $subscriber->transaction->CustomerName }}          <b>Customer Mobile:</b> {{ $subscriber->transaction->CustomerMobile }} </pre>
        </div>

        <div class="row">
            <pre> <b>Payment Gateway:</b> {{ $subscriber->transaction->PaymentGateway }}     <b>Payment Id:</b> {{ $subscriber->transaction->PaymentId }} </pre>
        </div>

        <div class="row">
            <pre> <b>Card Number:</b> {{ $subscriber->transaction->CardNumber }}      <b>Payment Date:</b> {{ date_create($subscriber->transaction->create_at)->format('l d-m-Y h a') }} </pre>
        </div>
        @endif
    </div>
</body>
</html>