<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanupOldTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Whether this is a dry run (no actual deletion).
     */
    protected bool $isDryRun;

    /**
     * Create a new job instance.
     */
    public function __construct(bool $isDryRun = false)
    {
        $this->isDryRun = $isDryRun;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cutoffDate = now()->subDays(30);
        
        // Get tasks older than 30 days
        $oldTasks = Task::where('created_at', '<', $cutoffDate)->get();
        
        $deletedCount = 0;
        $deletedTaskIds = [];
        
        if ($this->isDryRun) {
            Log::info('DRY RUN: Task cleanup job would delete', [
                'total_tasks' => $oldTasks->count(),
                'cutoff_date' => $cutoffDate,
                'task_ids' => $oldTasks->pluck('id')->toArray(),
                'executed_at' => now(),
            ]);
            return;
        }
        
        foreach ($oldTasks as $task) {
            try {
                $taskId = $task->id;
                $taskTitle = $task->title;
                $userId = $task->user_id;
                
                $task->delete();
                
                $deletedCount++;
                $deletedTaskIds[] = $taskId;
                
                // Log each deletion
                Log::info('Old task deleted', [
                    'task_id' => $taskId,
                    'title' => $taskTitle,
                    'user_id' => $userId,
                    'created_at' => $task->created_at,
                    'deleted_at' => now(),
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to delete old task', [
                    'task_id' => $task->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        // Log summary
        Log::info('Task cleanup job completed', [
            'total_deleted' => $deletedCount,
            'deleted_task_ids' => $deletedTaskIds,
            'cutoff_date' => $cutoffDate,
            'executed_at' => now(),
        ]);
    }
}
