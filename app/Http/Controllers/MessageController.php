<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class MessageController extends Controller
{

    public function store(Request $request)
    {
       
        $request->validate([
            'body' => 'required|string',
            'room_id' => 'nullable|exists:rooms,id',
            'to_user_id' => 'nullable|exists:users,id',
        ]);
    
        $user = Auth::user();
    
        if (!$request->room_id && !$request->to_user_id) {
            return response()->json(['error' => 'room_id or to_user_id must be informed'], 422);
        }
    
        if ($request->room_id) {
            $room = Room::findOrFail($request->room_id);
            if (!$room->users->contains($user->id)) {
                return response()->json(['error' => 'You are not in the chat room'], 403);
            }
        }
    
        $message = Message::create([
            'user_id' => $user->id,
            'room_id' => $request->room_id,
            'to_user_id' => $request->to_user_id,
            'body' => $request->body,
        ]);

        $message->load('user');

        \Log::info('Message created', [
            'id' => $message->id,
            'room_id' => $message->room_id,
            'user' => $message->user->name,
            'body' => $message->body
        ]);
    
        $event = new MessageSent($message);
        broadcast($event);
    
        \Log::info('MessageSent event got in');
        
        return response()->json($message, 201);
        
    }
    

    public function roomMessages(Room $room)
    {
        $user = Auth::user();
        if (!$room->users->contains($user->id)) {
            return response()->json(['error' => 'You do not belong to this chat room'], 403);
        }

        $messages = $room->messages()->with('user')->get();
        return response()->json($messages);
    }

    public function privateMessages(User $user)
    {
        $authUser = Auth::user();

        $messages = Message::where(function($q) use ($authUser, $user) {
            $q->where('user_id', $authUser->id)
              ->where('to_user_id', $user->id);
        })->orWhere(function($q) use ($authUser, $user) {
            $q->where('user_id', $user->id)
              ->where('to_user_id', $authUser->id);
        })->with('user')->get();

        return response()->json($messages);
    }
}
