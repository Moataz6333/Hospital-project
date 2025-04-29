<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Follower;

class Eventt extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'description',
        'date'
    ];
    public function followers() {
        return $this->hasMany(Follower::class);
    }
}
