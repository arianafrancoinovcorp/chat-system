<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Room;


//Chat room messages
Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    $room = Room::find($roomId);
    return $room && $room->users->contains($user->id);
    
});


//Direct Messages
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
