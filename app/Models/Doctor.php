<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TimeTable;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Appointment;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'birthdate',
        'national_id',
        'specialty',
        'experiance',
        'salary',
        'phone',
        'clinic_id',
        'profile',
        'price',
    ];
    protected $casts = [
        'salary' => 'integer',
        'price' => 'integer',
    ];
    

    public function clinic(){
        return $this->belongsTo(Clinic::class);
    }
    public function timeTable(){
        return $this->hasOne(TimeTable::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function appointments()  {
        return $this->hasMany(Appointment::class);
    }
}
