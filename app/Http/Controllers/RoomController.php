<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class RoomController extends Controller
{
    use AuthorizesRequests;

    /**
     * kistof rooms
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        /** @var \Illuminate\Database\Eloquent\Collection $rooms */
        $rooms = $user->rooms()->with('users')->get();

        return view('rooms.index', compact('rooms'));
    }

    /**
     * shows details of the room
     *
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Room $room)
    {
        $this->authorize('view', $room); // policy

        $room->load('users', 'messages.user');
        return view('rooms.show', compact('room'));
    }

    public function create()
    {
        $this->authorize('create', Room::class);
        return view('rooms.create');
    }

    /**
     * creates a new chat room --- admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
{
    $this->authorize('create', Room::class);

    $request->validate([
        'name' => 'required|string|max:255',
        'avatar' => 'nullable|image|max:2048',
    ]);

    $avatarPath = null;
    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('rooms', 'public');
    }

    $room = Room::create([
        'name' => $request->name,
        'avatar' => $avatarPath,
    ]);

    $room->users()->attach(Auth::id());

    return redirect()->route('rooms.index')->with('success', 'Room created successfully!');
}

    /**
     * invite a user to the chat room
     *
     * @param Room $room
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteUser(Room $room, User $user)
    {
        $this->authorize('update', $room);

        if (!$room->users->contains($user->id)) {
            $room->users()->attach($user->id);
        }

        return response()->json([
            'message' => "User {$user->name} added to the chat room {$room->name}"
        ]);
    }
}
