<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Room;

Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    $room = Room::find($roomId);
    return $room && $room->users->contains($user->id);
});
