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
            justify-content: space-between;
            padding: 5px 0;
        }

        .col {
            width: 48%;
        }

        h4 {
            margin-bottom: 5px;
        }

        hr {
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Registration Invoice</h1>
        </div>

        <div class="row">
            <div class="col">
                <h4>Patient Name:</h4>
                <p>{{$transaction->patien->name}}</p>
            </div>
            <div class="col">
                <h4>Patient Phone:</h4>
                <p>{{$transaction->patien->phone}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Appointment Date:</h4>
                <p>{{date_create($transaction->appointment->date)->format('l d-m-Y')}}</p>
            </div>
            <div class="col">
                <h4>Type:</h4>
                <p>{{$transaction->appointment->type}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Appointment Doctor:</h4>
                <p>{{$transaction->appointment->doctor->user->name}}</p>
            </div>
            <div class="col">
                <h4>Clinic:</h4>
                <p>{{$transaction->appointment->doctor->clinic->name}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Appointment Price:</h4>
                <p>{{$transaction->appointment->doctor->price}} {{config('app.currency')}}</p>
            </div>
            <div class="col">
                <h4>Paid:</h4>
                <p>@if ($transaction->appointment->paid) Yes @else No @endif</p>
            </div>
        </div>

        <hr>

        <h2 style="text-align: center;">Payment Details</h2>

        <div class="row">
            <div class="col">
                <h4>Payment Method:</h4>
                <p>{{$transaction->appointment->payment_method}}</p>
            </div>
            <div class="col">
                <h4>Registration:</h4>
                <p>{{$transaction->appointment->registration_method}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Invoice Id:</h4>
                <p>{{$transaction->InvoiceId}}</p>
            </div>
            <div class="col">
                <h4>Invoice Reference:</h4>
                <p>{{$transaction->InvoiceReference}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Invoice Value:</h4>
                <p>{{$transaction->InvoiceValue}}</p>
            </div>
            <div class="col">
                <h4>Currency:</h4>
                <p>{{$transaction->Currency}} (= {{env("MYFATOORAH_EXCHANGE_RATE")}} EGP)</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Customer Name:</h4>
                <p>{{$transaction->CustomerName}}</p>
            </div>
            <div class="col">
                <h4>Customer Mobile:</h4>
                <p>{{$transaction->CustomerMobile}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Payment Gateway:</h4>
                <p>{{$transaction->PaymentGateway}}</p>
            </div>
            <div class="col">
                <h4>Payment Id:</h4>
                <p>{{$transaction->PaymentId}}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h4>Card Number:</h4>
                <p>{{$transaction->CardNumber}}</p>
            </div>
            <div class="col">
                <h4>Payment Date:</h4>
                <p>{{date_create($transaction->create_at)->format('l d-m-Y h a')}}</p>
            </div>
        </div>
    </div>
</body>

</html>
