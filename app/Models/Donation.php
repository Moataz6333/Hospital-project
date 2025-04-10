<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'phone',
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
}
