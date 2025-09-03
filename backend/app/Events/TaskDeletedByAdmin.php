<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;

class TaskDeletedByAdmin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public int $taskId, public int $userId)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.tasks'),
            new PrivateChannel('tasks.user.' . $this->userId),
        ];
    }
}



