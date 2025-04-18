<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use App\Models\Donation;

class Patient extends Model
{
    use HasFactory;
    protected $fillable =['name','phone','age','gender','national_id'];

    public function appointment()  {
        return $this->hasOne(Appointment::class);
    }
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
    public function donation()  {
        return $this->hasOne(Donation::class);
    }
}
