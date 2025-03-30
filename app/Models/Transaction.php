<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=[
        'patien_id',
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
    public function patien()  {
        return $this->belongsTo(Patien::class);
    }
    public function appointment()  {
        return $this->belongsTo(Appointment::class);
    }
}
