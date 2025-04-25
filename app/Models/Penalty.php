<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\Doctor;
class Penalty extends Model
{
    use HasFactory;
    protected $fillable=[
        'days',
        'description',
        'employee_id',
        'doctor_id',
        'month',
    ];
    public function employee()  {
        return $this->belongsTo(Employee::class);
    }
    public function doctor()  {
        return $this->belongsTo(Doctor::class);
    }
}
