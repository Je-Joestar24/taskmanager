<?php

namespace Tests\Feature\Admin;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminTasksControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected Task $task;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
        $this->task = Task::factory()->create(['user_id' => $this->regularUser->id]);
    }

    public function test_admin_can_delete_any_task(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/admin/tasks/{$this->task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted successfully by admin.',
                'deleted_task' => [
                    'id' => $this->task->id,
                    'title' => $this->task->title,
                    'user_id' => $this->regularUser->id,
                ]
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $this->task->id]);
    }

    public function test_admin_can_delete_task_from_different_user(): void
    {
        Sanctum::actingAs($this->admin);

        $anotherUser = User::factory()->create(['role' => 'user']);
        $anotherTask = Task::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->deleteJson("/api/admin/tasks/{$anotherTask->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted successfully by admin.',
                'deleted_task' => [
                    'id' => $anotherTask->id,
                    'title' => $anotherTask->title,
                    'user_id' => $anotherUser->id,
                ]
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $anotherTask->id]);
    }

    public function test_regular_user_cannot_access_admin_delete_endpoint(): void
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->deleteJson("/api/admin/tasks/{$this->task->id}");

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized. Admin access only.']);
    }

    public function test_guest_cannot_access_admin_delete_endpoint(): void
    {
        $response = $this->deleteJson("/api/admin/tasks/{$this->task->id}");

        $response->assertStatus(401);
    }

    public function test_admin_delete_returns_404_for_nonexistent_task(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/admin/tasks/99999");

        $response->assertStatus(404);
    }

    public function test_admin_delete_reorders_remaining_tasks(): void
    {
        Sanctum::actingAs($this->admin);

        // Create multiple tasks for the same user
        $task1 = Task::factory()->create(['user_id' => $this->regularUser->id, 'order' => 1]);
        $task2 = Task::factory()->create(['user_id' => $this->regularUser->id, 'order' => 2]);
        $task3 = Task::factory()->create(['user_id' => $this->regularUser->id, 'order' => 3]);

        // Delete the middle task
        $response = $this->deleteJson("/api/admin/tasks/{$task2->id}");

        $response->assertStatus(200);

        // Check that remaining tasks are reordered
        $this->assertDatabaseHas('tasks', ['id' => $task1->id, 'order' => 1]);
        $this->assertDatabaseHas('tasks', ['id' => $task3->id, 'order' => 2]);
        $this->assertDatabaseMissing('tasks', ['id' => $task2->id]);
    }
}
