<?php

namespace App\Events;

use App\Page;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PageRouteCreated extends Event implements ShouldBroadcast
{
    use SerializesModels;
    public $id;
    public $title;
    public $issection;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Page $pagess)
    {
        
        $this->id = $pagess->id;
        $this->title = $pagess->title;
        $this->issection = $pagess->issection;

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
