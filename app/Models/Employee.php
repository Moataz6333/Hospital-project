<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Penalty;

class Employee extends Model
{
    use HasFactory;
    protected $fillable =[
        'phone',
        'home_phone',
        'birthDate',
        'user_id',
        'salary',
        'gender',
        'national_id',
        'address',
        'profile',

    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function penalty() {
        return $this->hasOne(Penalty::class);
    }
}
