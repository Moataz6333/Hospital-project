<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use App\Models\TimeTable;

class Clinic extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'place',
        'description',
        'photo'
        
    ];
    public function doctors(){
        return $this->hasMany(Doctor::class);
    }
   
}
