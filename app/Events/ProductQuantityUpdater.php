<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductQuantityUpdater
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $productDetails;
    /**
     * Create a new event instance.
     * product detail array
     * {
     *  {
     *    'product_id' => 1,
     *     'quantity' =>1
     *     'type' => ProductQuantityType::TYPE_DECREMENT
     *  },
     *  {
     *    'product_id' => 2,
     *     'quantity' =>2
     *     'type' => ProductQuantityType::TYPE_INCREMENT
     *  },
     * }
     */
    public function __construct($productDetails)
    {
        $this->productDetails = $productDetails;
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
