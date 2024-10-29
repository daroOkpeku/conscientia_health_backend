<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
     public $sender_id;
     public $receiver_id;
     public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($sender_id, $receiver_id, $message)
    {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->message = $message;
        $randnum = rand(00000, 99999);
        $chat = Chat::where(['sender_id'=>$sender_id, "receiver_id"=>$receiver_id])->first();
        if($chat){
            Chat::create([
                "sender_id"=>$sender_id,
                "receiver_id"=>$receiver_id,
                "message"=>$message,
                "chat_id"=>$chat->chat_id
            ]);
        }else{
            Chat::create([
                "sender_id"=>$sender_id,
                "receiver_id"=>$receiver_id,
                "message"=>$message,
                "chat_id"=>$randnum
            ]);
        }
    }



    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {

        return [
            new PrivateChannel('chat.' . $this->sender_id),
             new PrivateChannel('chat.' . $this->receiver_id),
        ];

    }

    public function broadcastAs(){
        return 'chat_message';
    }
}
