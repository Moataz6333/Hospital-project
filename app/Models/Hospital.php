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
        'balance',
    ];
    protected $hidden =[
        'id',
        'created_at',
        'updated_at',
        'salary'
    ];
    public function increaseBalance($amount){
        $current =$this->balance;
        $this->update([
            'balance'=>$current + (double) $amount
        ]);
    }
    public function decreaseBalance($amount){
        $current =$this->balance;
        if( $current > (double) $amount){
            $this->update([
                'balance'=>$current - (double) $amount
            ]);
        }else{
            return redirect()->back()->with('failed','the amount is bigger than balance');
        }
    }
}
