<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable=[
        'patient_id',
        'doctor_id',
        'date',
        'type',
        'payment_method',
        'registration_method',
        'paid',
        'verified',
        'status',
        'canceled_by',
        'amount_paid',
        
    ];

    public function patient(){
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
    public function transaction() {
        return $this->hasOne(Transaction::class);
    }
}
