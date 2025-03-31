<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class Patient extends Model
{
    use HasFactory;
    protected $fillable =['name','phone','age','gender'];

    public function appointment()  {
        return $this->hasMany(Appointment::class);
    }
}
