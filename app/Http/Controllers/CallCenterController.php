<?php

namespace App\Http\Controllers;

use App\Events\MessageSendedEvent;
use App\Jobs\MessageUpdatedJob;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class CallCenterController extends Controller
{
    public function index() {
        $chats=Chat::where('user_id',auth()->user()->id)->get()->load(['sender','messages']);
        return view('call-centers.index',compact('chats'));
    }
    public function chat($id) {
        $mainChat =Chat::where('uuid',$id)->first()->load(['sender','messages']);
        if ($mainChat) {
            $chats=Chat::where('user_id',auth()->user()->id)->get()->load(['sender','messages']);
            return view('call-centers.chat',compact('mainChat','chats'));
        }else{
            abort(404);
        }
       
    }
    public function send(Request $request) {
        $request->validate([
            'message'=>'required|min:2',
            "uuid" => "required|min:2|max:255|uuid",
        ]);
        $chat=Chat::where('uuid',$request->uuid)->first()->load('sender');
        if ($chat) {
            $message=Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $chat->sender_id,
                'receiver_id' => $chat->user_id,
                'message' => $request->message,
                'sendedBy'=>'user'
            ]);
            if ($message) {
                event(new MessageSendedEvent($message));
                return response()->json(['message'=>'message sended successfully'], 200);
            }
        } else {
            abort(404);
        }
        

    }
    public function destroy($id)  {
        $chat =Chat::where('uuid',$id)->first();
        if ($chat) {
            $chat->delete();
            return to_route('centers.index')->with('success','chat deleted successfully!');
        }else{
            abort(404);
        }
    }

}
