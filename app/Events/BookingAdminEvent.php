<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingAdminEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public   $firstname;
    public  $lastname;
    public $state;
    public $doctor;
    public $email; 
    public $phone;
    public $comment; 
    public $visit_type;
    public  $code;
    public  $is_used;
    public $schedule_time;
    public $mean_payment;
    public $country;
    /**
     * Create a new event instance.
     */
    public function __construct(
        $firstname, $lastname, $state, $doctor, $email, $phone,
        $comment, $visit_type,  $code,  $is_used,
        $schedule_time,
        $mean_payment,
        $country
    )
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->state = $state;
        $this->doctor = $doctor;
        $this->email = $email;
        $this->phone = $phone;
        $this->visit_type = $visit_type;
        $this->code = $code;
        $this->is_used = $is_used;
        $this->comment =$comment;
        $this->schedule_time = $schedule_time;
        $this->mean_payment = $mean_payment;
        $this->country = $country;
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
