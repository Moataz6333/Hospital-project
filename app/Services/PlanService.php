<?php

namespace App\Services;

use App\Models\Follower;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Plan;
use App\Models\Subscriber;
use Illuminate\Support\Facades\DB;

class PlanService
{
    public function register($data, $registeration_method, $plan)
    {
        $patient = new Patient();
        $patient->name = $data['name'];
        $patient->phone = $data['phone'];
        $patient->gender = $data['gender'];
        $patient->national_id = $data['national_id'];
        $patient->age = (int) $data['age'];
        $patient->save();

        $subsecriber = new Subscriber();
        $subsecriber->registeration_method = $registeration_method;
        $subsecriber->subscribtion_id = uuid_create(UUID_TYPE_DEFAULT);
        $subsecriber->patient_id = $patient->id;
        $subsecriber->plan_id = $plan->id;
        $subsecriber->subscription_date = date_create()->modify("+ $plan->period days");

        if ($data['payment_method'] == 'cash') {
            $subsecriber->payment_method = 'cash';
            if (array_key_exists('paid',$data)) {
                $subsecriber->paid =true;
                Hospital::first()->increaseBalance($plan->price);
            }
            $subsecriber->save();
            return $subsecriber;
        }elseif ($data['payment_method'] == 'online') {
            $subsecriber->payment_method = 'online';
            $subsecriber->save();
            return $subsecriber;
        }else {
            abort(403);
        }
       
    }
    public function register_online($data, $plan)
    {
        $patient = new Patient();
        $patient->name = $data['name'];
        $patient->phone = $data['phone'];
        $patient->gender = $data['gender'];
        $patient->national_id = $data['national_id'];
        $patient->age = (int) $data['age'];
        $patient->save();

        $subsecriber = new Subscriber();
        $subsecriber->registeration_method = 'online';
        $subsecriber->subscribtion_id = uuid_create(UUID_TYPE_DEFAULT);
        $subsecriber->patient_id = $patient->id;
        $subsecriber->plan_id = $plan->id;
        $subsecriber->subscription_date = date_create()->modify("+ $plan->period days");

       
            $subsecriber->payment_method = 'online';
            $subsecriber->save();
            return $subsecriber;
       
       
    }
    public function update($data,$subsecriber) {
        $patient = $subsecriber->patient;
        $patient->name = $data['name'];
        $patient->phone = $data['phone'];
        $patient->gender = $data['gender'];
        $patient->national_id = $data['national_id'];
        $patient->age = (int) $data['age'];
        $patient->save();
        

        if ($data['payment_method'] == 'cash') {
            $subsecriber->payment_method = 'cash';
            if (array_key_exists('paid',$data)) {
                $subsecriber->paid =true;
                Hospital::first()->increaseBalance($subsecriber->plan->price);
            }else {
                $subsecriber->paid =false;
                Hospital::first()->decreaseBalance($subsecriber->plan->price);
            }
            $subsecriber->save();
            return $subsecriber;
        }elseif ($data['payment_method'] == 'online') {
            $subsecriber->payment_method = 'online';
        }else {
            abort(403);
        }
       
    }
    public function theMostPopular()  {
        $most =DB::table('subscribers')
        ->select('plan_id',DB::raw('Count(*) as count'))
        ->groupBy('plan_id')
        ->orderByDesc('count')
        ->limit(1)
        ->pluck('plan_id')
        ->first();
        if ($most) {
            return $most;
        } else {
            return  Plan::first()->id;
        }
        
       
    }
    public function follow($email,$event_id)  {
        if (! Follower::where('email',$email)->where('eventt_id',$event_id)->first()) {
          $follower=  Follower::create([
                'email'=>$email,
                'eventt_id'=>$event_id
            ]);
            return $follower;
        }else {
            return false;
        }
    }
}
