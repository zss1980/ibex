<?php

namespace App\Events;

use App\Page;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PageRouteDeleted extends Event implements ShouldBroadcast
{
    use SerializesModels;
    
    public $isdeleted;
    public $test;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($index)
    {
       

        $this->isdeleted = $index;
        $this->test = $index;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['pageRouteAction'];
    }
}
