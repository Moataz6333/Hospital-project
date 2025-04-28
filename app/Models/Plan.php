<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subscriber;

class Plan extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'period',
        'icon',
        'price',
        'features'
    ];
    public function subscribers() {
        return $this->hasMany(Subscriber::class);
    }
}
