<?php

namespace Tests\Unit;

use App\Jobs\CleanupOldTasks;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CleanupOldTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_cleanup_job_deletes_tasks_older_than_30_days(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create old tasks (older than 30 days)
        $oldTask1 = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(31),
        ]);
        
        $oldTask2 = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(35),
        ]);
        
        // Create recent tasks (less than 30 days old)
        $recentTask1 = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(29),
        ]);
        
        $recentTask2 = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(15),
        ]);
        
        // Mock Log facade
        Log::shouldReceive('info')->times(3); // 2 deletions + 1 summary
        
        // Run the cleanup job
        $job = new CleanupOldTasks(false);
        $job->handle();
        
        // Assert old tasks are deleted
        $this->assertDatabaseMissing('tasks', ['id' => $oldTask1->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $oldTask2->id]);
        
        // Assert recent tasks are still there
        $this->assertDatabaseHas('tasks', ['id' => $recentTask1->id]);
        $this->assertDatabaseHas('tasks', ['id' => $recentTask2->id]);
    }
    
    public function test_cleanup_job_dry_run_does_not_delete_tasks(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create old tasks
        $oldTask = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(31),
        ]);
        
        // Mock Log facade
        Log::shouldReceive('info')->once(); // Only dry run log
        
        // Run the cleanup job in dry run mode
        $job = new CleanupOldTasks(true);
        $job->handle();
        
        // Assert task is still there
        $this->assertDatabaseHas('tasks', ['id' => $oldTask->id]);
    }
    
    public function test_cleanup_job_handles_exception_gracefully(): void
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create old task
        $oldTask = Task::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(31),
        ]);
        
        // Run the cleanup job - should not throw any exceptions
        $job = new CleanupOldTasks(false);
        $job->handle();
        
        // Job should complete without throwing exception
        $this->assertTrue(true);
    }
}
