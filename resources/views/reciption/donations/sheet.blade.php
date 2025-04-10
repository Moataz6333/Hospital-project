<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Donation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .header {
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
        <h1 style="text-align: center; margin:20px 20px;">Donation Invoice {{$donation->id}} </h1>
        <a href="{{ route('donation.export', $donation->transaction->PaymentId) }}" style="margin-right: 20px">export pdf</a>
    </div>
    <div class="main-div">
        <div class="row">
            <div>
                <h4>Customer Name :</h4>
                <p class="p-text">
                    {{ $donation->name }}
                </p>
            </div>
            <div>
                <h4>Customer Phone :</h4>
                <p class="p-text">
                    {{ $donation->phone }}
                </p>
            </div>
            <div>
                <h4>Customer National id :</h4>
                <p class="p-text">
                    {{ $donation->national_id }}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Donation Value :</h4>
                <p class="p-text">
                    {{ $donation->value .' '. $donation->currency }}
                </p>
            </div>
            <div>
                <h4>Registeration Method :</h4>
                <p class="p-text">
                    {{ $donation->registeration_method }}
                </p>
            </div>
            <div>
                <h4>Payment Method :</h4>
                <p class="p-text">
                    {{ $donation->payment_method }}
                </p>
            </div>
        </div>
       
        
        <hr style="margin:20px;">
        @if ($donation->transaction)
            
        <h1 style="text-align: center; margin:10px 0;">Payment Details</h1>

        <div class="row">
            <div>
                <h4>Invoice Id :</h4>
                <p class="p-text">
                    {{ $donation->transaction->InvoiceId }}
                </p>
            </div>
            <div>
                <h4>Invoice Reference :</h4>
                <p class="p-text">
                    {{ $donation->transaction->InvoiceReference }}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Invoice Value :</h4>
                <p class="p-text">
                    {{ $donation->transaction->InvoiceValue }}
                </p>
            </div>
            <div>
                <h4>Currency :</h4>
                <p class="p-text">
                    {{ $donation->transaction->Currency }} (= {{ env('MYFATOORAH_EXCHANGE_RATE') }} EGP)
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Customer Name :</h4>
                <p class="p-text">
                    {{ $donation->transaction->CustomerName }}
                </p>
            </div>
            <div>
                <h4>Customer Mobile :</h4>
                <p class="p-text">
                    {{ $donation->transaction->CustomerMobile }}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Payment Gateway :</h4>
                <p class="p-text">
                    {{ $donation->transaction->PaymentGateway }}
                </p>
            </div>
            <div>
                <h4>Payment Id :</h4>
                <p class="p-text">
                    {{ $donation->transaction->PaymentId }}
                </p>
            </div>
        </div>
        <div class="row">
            <div>
                <h4>Card Number :</h4>
                <p class="p-text">
                    {{ $donation->transaction->CardNumber }}
                </p>
            </div>
            <div>
                <h4>Payment Date :</h4>
                <p class="p-text">
                    {{ date_create($donation->transaction->create_at)->format('l d-m-Y h a') }}
                </p>
            </div>
        </div>
        @endif

    </div>
</body>

</html>
