<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Task $task)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('tasks.user.' . $this->task->user_id)];
    }
}



