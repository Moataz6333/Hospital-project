<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class Patien extends Model
{
    use HasFactory;
    protected $fillable =['name','phone'];

    public function appointment()  {
        return $this->hasMany(Appointment::class);
    }
}
