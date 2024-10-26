<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $otheruserId;
    public $chatId;
    public $isTyping;
    /**
     * Create a new event instance.
     */
    public function __construct($otheruserId, $chatId, $isTyping)
    {
        $this->otheruserId = $otheruserId;
        $this->chatId = $chatId;
        $this->isTyping = $isTyping;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('typing.' . $this->otheruserId),
        ];
    }
}
