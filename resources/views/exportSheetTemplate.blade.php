<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sheet</title>
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
        }

        .row {
            display: flex;
            /* justify-content: center; */
            padding: 5px 0;
            width: 100%;
            gap: 15px;
        }

        hr {
            margin: 10px 0;
        }

        h1 {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Registration Invoice</h1>
        </div>

        <div class="row">
            <pre> <b>Patient Name:</b>   {{ $transaction->patient->name }}          <b>Patient Phone:</b>  {{ $transaction->patient->phone }}  </pre>
        </div>

        <div class="row">
            <pre> <b>Appointment Date:</b> {{ date_create($transaction->appointment->date)->format('l d-m-Y') }}        <b>Type:</b>  {{ $transaction->appointment->type }} </pre>
        </div>

        <div class="row">
            <pre> <b>Doctor:</b> {{ $transaction->appointment->doctor->user->name }}     <b>Clinic:</b> {{ $transaction->appointment->doctor->clinic->name }} </pre>
        </div>

        <div class="row">
            <pre> <b>Appointment Price:</b> {{ $transaction->appointment->doctor->price }} {{ config('app.currency') }}            <b>Paid:</b> @if ($transaction->appointment->paid) Yes @else No @endif </pre>
        </div>

        <hr>

        <h1 style="text-align: center;">Payment Details</h1>

        <div class="row">
            <pre> <b>Payment Method:</b> {{ $transaction->appointment->payment_method }}           <b>Registration:</b> {{ $transaction->appointment->registration_method }} </pre>
        </div>

        <div class="row">
            <pre> <b>Invoice Id:</b> {{ $transaction->InvoiceId }}        <b>Invoice Reference:</b> {{ $transaction->InvoiceReference }} </pre>
        </div>

        <div class="row">
            <pre> <b>Invoice Value:</b> {{ $transaction->InvoiceValue }}         <b>Currency:</b> {{ $transaction->Currency }} (= {{ env('MYFATOORAH_EXCHANGE_RATE') }} EGP) </pre>
        </div>

        <div class="row">
            <pre> <b>Customer Name:</b> {{ $transaction->CustomerName }}          <b>Customer Mobile:</b> {{ $transaction->CustomerMobile }} </pre>
        </div>

        <div class="row">
            <pre> <b>Payment Gateway:</b> {{ $transaction->PaymentGateway }}     <b>Payment Id:</b> {{ $transaction->PaymentId }} </pre>
        </div>

        <div class="row">
            <pre> <b>Card Number:</b> {{ $transaction->CardNumber }}      <b>Payment Date:</b> {{ date_create($transaction->create_at)->format('l d-m-Y h a') }} </pre>
        </div>
    </div>
</body>

</html>
