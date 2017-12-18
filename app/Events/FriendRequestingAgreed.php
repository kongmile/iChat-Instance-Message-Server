<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\FriendRequesting;

class FriendRequestingAgreed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $friendRequesting;
    public $to;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FriendRequesting $friendRequesting)
    {
        $this->friendRequesting = $friendRequesting;
        $this->to = $friendRequesting->toUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat.user.'. $this->friendRequesting->from);
    }

    public function broadcastWith()
    {
        $friendRequestion = $this->friendRequesting->toArray();
        $friendRequestion['from'] = $this->friendRequesting->fromUser;
        $friendRequestion['to'] = $this->friendRequesting->toUser;
        return $friendRequestion;
    }
}
