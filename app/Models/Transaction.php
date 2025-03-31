<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=[
        'patient_id',
        'appointment_id',
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
}
