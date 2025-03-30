<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'address',
        'phone1',
        'phone2',
        'hotline',
        'email',
        'salary',
    ];
    protected $hidden =[
        'id',
        'created_at',
        'updated_at',
        'salary'
    ];
}
