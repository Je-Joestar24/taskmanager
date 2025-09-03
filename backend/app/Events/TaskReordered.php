<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;

class TaskReordered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public int $userId)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('tasks.user.' . $this->userId)];
    }
}



