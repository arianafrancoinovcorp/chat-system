<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DirectMessageController;

Route::get('/', function () {
    return view('welcome');
});

// Jetstream authentication
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard 
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rooms
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::post('/rooms/{room}/invite', [RoomController::class, 'invite'])->name('rooms.invite');


    // Messages
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/room/{room}', [MessageController::class, 'roomMessages'])->name('messages.room');
    Route::get('/messages/private/{user}', [MessageController::class, 'privateMessages'])->name('messages.private');

    // Direct Messages
    Route::get('/direct', [App\Http\Controllers\DirectMessageController::class, 'index'])->name('direct.index');
    Route::get('/direct/{user}', [App\Http\Controllers\DirectMessageController::class, 'show'])->name('direct.show');
});
