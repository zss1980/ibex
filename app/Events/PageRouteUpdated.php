<?php

namespace App\Events;

use App\Page;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PageRouteUpdated extends Event implements ShouldBroadcast
{
    use SerializesModels;
    public $title;
    public $index;
    public $issection;
    public $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($index, Page $page)
    {
        $this->index = $index;
        $this->title = $page->title;
        $this->issection = $page->issection;
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
