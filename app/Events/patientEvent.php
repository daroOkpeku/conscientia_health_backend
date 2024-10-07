<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class patientEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $chart_id;
    public $first_name;
    public $last_name;
    /**
     * Create a new event instance.
     */
    public function __construct($chart_id, $first_name, $last_name)
    {
        $this->chart_id = $chart_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
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
