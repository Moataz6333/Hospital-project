<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patien;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable=[
        'patien_id',
        'doctor_id',
        'date',
        'type',
        'payment_method',
        'registration_method',
        'paid',
        'verified',
        'status',
        'canceled_by',
    ];

    public function patien(){
        return $this->belongsTo(Patien::class,'patien_id','id');
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
    public function transaction() {
        return $this->hasOne(Transaction::class);
    }
}
