<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectMessageController extends Controller
{
    public function index()
{
    $authUser = Auth::user();
    
    $userIds = Message::where(function($q) use ($authUser) {
        $q->where('user_id', $authUser->id)
          ->whereNotNull('to_user_id');
    })->orWhere(function($q) use ($authUser) {
        $q->where('to_user_id', $authUser->id);
    })
    ->get()
    ->map(function($message) use ($authUser) {
        return $message->user_id === $authUser->id 
            ? $message->to_user_id 
            : $message->user_id;
    })
    ->unique()
    ->filter();
    
    $usersWithMessages = User::whereIn('id', $userIds)->get();
    
    $allUsers = User::where('id', '!=', $authUser->id)->get();
    
    return view('direct.index', compact('usersWithMessages', 'allUsers'));
}

public function show(User $user)
{
    $authUser = Auth::user();

    if ($authUser->id === $user->id) {
        return redirect()->route('dashboard')->with('error', 'Cannot send messages to yourself');
    }

    Message::where('user_id', $user->id)
           ->where('to_user_id', $authUser->id)
           ->whereNull('read_at')
           ->update(['read_at' => now()]);

    $messages = Message::where(function($q) use ($authUser, $user) {
        $q->where('user_id', $authUser->id)
          ->where('to_user_id', $user->id);
    })->orWhere(function($q) use ($authUser, $user) {
        $q->where('user_id', $user->id)
          ->where('to_user_id', $authUser->id);
    })
    ->with('user')
    ->orderBy('created_at', 'asc')
    ->get();

    return view('direct.show', compact('user', 'messages'));
}

}