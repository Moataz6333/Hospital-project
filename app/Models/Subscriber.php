<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Plan;
use App\Models\Transaction;

class Subscriber extends Model
{
    use HasFactory;
    protected $fillable=[
        'subscribtion_id',
        'patient_id',
        'plan_id',
        'subscription_date',
        'registeration_method',
        'payment_method',
        'paid',
    ];

    public function patient()  {
        return $this->belongsTo(Patient::class);
    }
    public function plan(){
        return $this->belongsTo(Plan::class);
    }
    public function transaction(){
        return $this->hasOne(Transaction::class,'subsciber_id');
    }
}
