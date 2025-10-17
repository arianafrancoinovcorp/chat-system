<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
        \Log::info('MessageSent event build', ['message_id' => $message->id]);
    }

    public function broadcastOn(): PrivateChannel
    {
        $channel = 'room.' . $this->message->room_id;
        \Log::info('Broadcasting  ' . $channel);
        return new PrivateChannel($channel);
    }

    public function broadcastWith(): array
    {
        $data = [
            'message' => $this->message->toArray()
        ];
        \Log::info('Sending data', $data);
        return $data;
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}