<?php

namespace App\Events\ServiceData;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResponseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $response;

    /**
     * Create a new event instance.
     *
     * @param $response
     */
    public function __construct($response)
    {
        $this->response=$response;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
