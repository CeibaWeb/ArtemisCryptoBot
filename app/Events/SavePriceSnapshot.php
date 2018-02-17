<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\PriceSnapshot;

class SavePriceSnapshot
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $snapshot;

    public $coin;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PriceSnapshot $snapshot)
    {
        $this->snapshot = $snapshot->load('coin');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
