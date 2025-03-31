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
        }
        .header{
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;

        }

        .main-div {
            margin: 30px auto;
            padding: 20px;
            width: 80%;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            margin: 20px 0;
        }

        .row div {
            display: flex;
            width: 100%;
            align-items: center;
            gap: 15px;
            justify-content: center;
        }

        @media (max-width:780px) {
            .main-div {
                width: 100%;
                margin: 0;
            }

            .row {
                flex-direction: column;
                gap: 15px;
            }

            .row div {
                justify-content: flex-start;
            }
        }
    </style>
</head>

<body>
   <div class="header">
    <h1 style="text-align: center; margin:20px 20px;">Registeration Invoice</h1>
    <a href="{{route('sheet.download',$transaction->PaymentId)}}" style="margin-right: 20px">export pdf</a>
   </div>
    <div class="main-div">
        <div class="row">
            <div>
                <h4>Patient Name :</h4>
                <p class="p-text">
                    {{$transaction->patient->name}}
                </p>
            </div>
            <div>
                <h4>Patient Phone :</h4>
                <p class="p-text">
                    {{$transaction->patient->phone}}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Appointment Date :</h4>
                <p class="p-text">
                    {{date_create($transaction->appointment->date)->format('l d-m-Y')}}
                </p>
            </div>
            <div>
                <h4>Type :</h4>
                <p class="p-text">
                    {{$transaction->appointment->type}}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Appointment Doctor :</h4>
                <p class="p-text">
                    {{$transaction->appointment->doctor->user->name}}
                </p>
            </div>
            <div>
                <h4>Clinic :</h4>
                <p class="p-text">
                    {{$transaction->appointment->doctor->clinic->name}}
                    
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Appointment Price :</h4>
                <p class="p-text">
                    {{$transaction->appointment->doctor->price }} {{config('app.currency')}}
                </p>
            </div>
            <div>
                <h4>Paid :</h4>
                <p class="p-text">
                        @if ($transaction->appointment->paid)
                            Yes
                        @else
                            No
                        @endif
                </p>
            </div>
        </div>
        <hr style="margin:20px;">
        
        <h1 style="text-align: center; margin:10px 0;">Payment Details</h1>

        <div class="row">
            <div>
                <h4>Payment Method :</h4>
                <p class="p-text">
                    {{$transaction->appointment->payment_method}}
                </p>
            </div>
            <div>
                <h4>Registration :</h4>
                <p class="p-text">
                    {{$transaction->appointment->registration_method}}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Invoice Id :</h4>
                <p class="p-text">
                    {{$transaction->InvoiceId}}
                </p>
            </div>
            <div>
                <h4>Invoice Reference :</h4>
                <p class="p-text">
                    {{$transaction->InvoiceReference}}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Invoice Value :</h4>
                <p class="p-text">
                    {{$transaction->InvoiceValue}}
                </p>
            </div>
            <div>
                <h4>Currency :</h4>
                <p class="p-text">
                    {{$transaction->Currency}} (= {{env("MYFATOORAH_EXCHANGE_RATE")}} EGP)
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Customer Name :</h4>
                <p class="p-text">
                    {{$transaction->CustomerName}}
                </p>
            </div>
            <div>
                <h4>Customer Mobile :</h4>
                <p class="p-text">
                    {{$transaction->CustomerMobile}}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Payment Gateway :</h4>
                <p class="p-text">
                    {{$transaction->PaymentGateway}}
                </p>
            </div>
            <div>
                <h4>Payment Id :</h4>
                <p class="p-text">
                    {{$transaction->PaymentId}}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Card Number :</h4>
                <p class="p-text">
                    {{$transaction->CardNumber}}
                </p>
            </div>
            <div>
                <h4>Payment Date :</h4>
                <p class="p-text">
                    {{date_create($transaction->create_at)->format('l d-m-Y h a')}}
                </p>
            </div>
        </div>
    </div>
</body>

</html>
