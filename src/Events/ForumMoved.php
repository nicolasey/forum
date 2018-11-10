<?php
namespace Nicolasey\Forum\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Nicolasey\Forum\Models\Forum;

class ForumMoved implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $forum;

    public function __construct(Forum $forum)
    {
        $this->forum = $forum;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('forum.'.$this->forum->id);
    }
}