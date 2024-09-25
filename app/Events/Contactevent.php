<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Contactevent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $companyemail;
    public $name;
    public $email;
    public $phone;
    public $subject;
    public $comment;
    /**
     * Create a new event instance.
     */
    public function __construct(
        $companyemail,
        $name,
        $email,
        $phone,
        $subject,
        $comment
    )
    {
        $this->companyemail = $companyemail;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->subject = $subject;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
