<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TasksController extends Controller
{
    /**
     * Remove the specified task (admin can delete any task).
     */
    public function destroy(Request $request, Task $task): JsonResponse
    {
        // Admin can delete any task
        $userId = $task->user_id;
        $taskTitle = $task->title;
        
        $task->delete();

        // Reorder remaining tasks to fill the gap
        $this->reorderTasksAfterDeletion($userId);

        // Clear cache for the task owner
        $this->clearUserTasksCache($userId);

        return response()->json([
            'message' => 'Task deleted successfully by admin.',
            'deleted_task' => [
                'id' => $task->id,
                'title' => $taskTitle,
                'user_id' => $userId,
            ]
        ]);
    }

    private function reorderTasksAfterDeletion(int $userId): void
    {
        $tasks = Task::where('user_id', $userId)
            ->orderBy('order')
            ->get();

        foreach ($tasks as $index => $task) {
            $task->update(['order' => $index + 1]);
        }
    }

    private function clearUserTasksCache(int $userId): void
    {
        Cache::forget("user_task_stats_{$userId}");
    }
}
