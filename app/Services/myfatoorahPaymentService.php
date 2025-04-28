<?php
namespace App\Services;

use App\Interfaces\PaymentInterface;

class myfatoorahPaymentService  implements PaymentInterface
{
    public function pay($appointment){
        if($appointment->registration_method == 'reception'){

            return redirect("myfatoorah/checkout?oid={$appointment->id}");
        }else{
            return response()->json([
                "message" => "appointment created",
                "link" => env("APP_URL") . "/myfatoorah/checkout?oid={$appointment->id}"
            ], 200);
        }
    }
    public function donate($donation) {
     
        if($donation->registeration_method == 'reception'){

            return redirect("myfatoorah/checkout?did={$donation->id}");
        }else{
            return response()->json([
                "message" => "donation created",
                "link" => env("APP_URL") . "/myfatoorah/checkout?did={$donation->id}"
            ], 200);
        }
    }
    public function subscribe($subscriber){
        if($subscriber->registeration_method == 'reception'){

            return redirect("myfatoorah/checkout?ssid={$subscriber->id}");
        }else{
            return response()->json([
                "message" => "subscriber created",
                "link" => env("APP_URL") . "/myfatoorah/checkout?ssid={$subscriber->id}"
            ], 200);
        }
    }
    
}

