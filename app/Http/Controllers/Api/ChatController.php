<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSendedEvent;
use App\Events\NewChatEvent;
use App\Http\Controllers\Controller;
use App\Jobs\MessageUpdatedJob;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Chat;
use App\Models\Sender;
use App\Models\User;

class ChatController extends Controller
{
    public function requestForChat(Request $request)
    {
        $request->validate([
            'name' => "required|min:2|max:255",
        ]);
        // $users = User::where('role', 'call-center')->where('isActive', true)->pluck('id')->toArray();
          $users=[21];
        if (count($users) != 0) {
            $user =  $users[array_rand($users)];
            $sender = Sender::create([
                'name' => $request->name
            ]);

            $chat = Chat::create([
                'uuid' => uuid_create(UUID_TYPE_DEFAULT),
                'sender_id' => $sender->id,
                'user_id' => $user,
            ]);
            return response()->json([
                "chat" => $chat->load(['messages', 'sender'])
            ], 200);
        } else {
            return response()->json([
                "message" => "There are no Call-Centers available now, Try to contact later!"
            ], 200);
        }
    }
    public function chat($id)
    {
        $chat = Chat::where('uuid', $id)->first();
        if ($chat) {
            return response()->json([
                "chat" => $chat->load('messages')
            ], 200);
        } else {
            abort(404);
        }
    }
    public function send(Request $request)
    {
        $request->validate([
            "uuid" => "required|min:2|max:255|uuid",
            "message" => "min:1|required"
        ]);
        $chat = Chat::where('uuid', $request->uuid)->first();
        if ($chat) {
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $chat->sender_id,
                'receiver_id' => $chat->user_id,
                'message' => $request->message,
                'sendedBy' => 'sender'
            ]);
            if ($message) {
                event(new MessageSendedEvent($message));
                event(new NewChatEvent($chat->load(['messages', 'sender'])));

                return response()->json(['message' => 'message sended successfully!'], 200);
            } else {
                return response()->json(['message' => 'Somthing Wrong!'], 403);
            }
        } else {
            abort(404);
        }
    }
}
