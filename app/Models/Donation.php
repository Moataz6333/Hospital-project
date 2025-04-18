<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;

class Donation extends Model
{
    use HasFactory;
    protected $fillable =[
        'patient_id',
        'national_id',
        'value',
        'currency',
        'registeration_method',
        'payment_method',
        'paid',
    ];
    public function transaction() {
        return $this->hasOne(Transaction::class,'donation_id');
    }
    public function patient() {
        return $this->belongsTo(Patient::class);
    }
}
