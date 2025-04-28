<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;
use App\Models\Donation;
use App\Models\Subscriber;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=[
        'patient_id',
        'appointment_id',
        'donation_id',
        'subsciber_id',
        'InvoiceId',
        'InvoiceReference',
        'InvoiceValue',
        'Currency',
        'CustomerName',
        'CustomerMobile',
        'PaymentGateway',
        'PaymentId',
        'CardNumber',
    ];
    public function patient()  {
        return $this->belongsTo(Patient::class);
    }
    public function appointment()  {
        return $this->belongsTo(Appointment::class);
    }
    public function donation()  {
        return $this->belongsTo(Donation::class);
    }
    public function subscriber() {
        return $this->belongsTo(Subscriber::class,'subsciber_id');
    }
}
