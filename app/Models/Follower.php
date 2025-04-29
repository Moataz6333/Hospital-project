<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Eventt;

class Follower extends Model
{
    use HasFactory;
    protected $fillable=[
        'email',
        'eventt_id'
    ];

    public function event() {
        return $this->belongsTo(Eventt::class);
    }
}
