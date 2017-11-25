<?php

namespace App\Listeners;

use App\Events\FriendRequestingCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFriendRequesting implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FriendRequestionSent  $event
     * @return void
     */
    public function handle(FriendRequestingCreated $event)
    {
        //
    }
}
