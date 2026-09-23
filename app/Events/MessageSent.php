<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        // Broadcast to a private channel for the receiver
        return [
            new PrivateChannel('chat.' . $this->message->receiver_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'content'    => $this->message->content,
            'sender_id'  => $this->message->sender_id,
            'created_at' => $this->message->created_at->format('H:i'),
            'sender'     => [
                'name'       => $this->message->sender->name,
                'avatar_url' => $this->message->sender->avatar_url,
            ],
        ];
    }
}
