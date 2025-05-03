<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;

class Message extends Model
{
    use HasFactory;
    protected $fillable=[
        'message',
        'chat_id',
        'sender_id',
        'receiver_id',
        'sendedBy',
    ];
    public function chat() {
        return $this->belongsTo(Chat::class);
    }
}
