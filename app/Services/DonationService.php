<?php
namespace App\Services;
 use App\Models\Donation;

class DonationService 
{
    public function registerOnline($data,$registration_method){
        $donation = Donation::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'national_id' => $data['national_id'],
            'value' => abs((float) $data['value']),
            'currency' => $data['currency'],
            'payment_method' => 'online',
            'registration_method' => $registration_method,
        ]);
        return $donation;
    }
}
