<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;

class TimeTable extends Model
{
    use HasFactory;
    protected $guarded =['id'];
    protected $hidden =['id','doctor_id','created_at','updated_at'];
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
