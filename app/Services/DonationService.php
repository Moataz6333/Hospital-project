<?php
namespace App\Services;
 use App\Models\Patient;
 use App\Models\Donation;

class DonationService 
{
    public function registerOnline($data,$registration_method){
       $patient = Patient::firstOrCreate(
            ['national_id' => $data['national_id'] ],
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
            ]
        );
        $donation = Donation::create([
            'patient_id' => $patient->id,
            'value' => abs((float) $data['value']),
            'currency' => $data['currency'],
            'payment_method' => 'online',
            'registration_method' => $registration_method,
        ]);
        return $donation;
    }
}
