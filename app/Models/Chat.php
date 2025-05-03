<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Message;
use App\Models\Sender;

class Chat extends Model
{
    use HasFactory;
    protected $fillable=[
        'uuid',
        'sender_id',
        'user_id',
    ];

    public function messages() {
        return $this->hasMany(Message::class);
    }
    public function sender() {
        return $this->belongsTo(Sender::class);
    }
}
