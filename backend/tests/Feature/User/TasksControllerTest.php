<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;

class TasksControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_view_their_tasks()
    {
        Sanctum::actingAs($this->user);
        
        $tasks = Task::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id', 'title', 'description', 'status', 
                            'priority', 'order', 'user_id', 'created_at', 'updated_at'
                        ]
                    ],
                    'meta' => [
                        'total', 'pending', 'completed', 'low_priority', 
                        'medium_priority', 'high_priority'
                    ]
                ]);

        $this->assertEquals(3, $response->json('meta.total'));
    }

    /** @test */
    public function user_can_filter_tasks_by_status()
    {
        Sanctum::actingAs($this->user);
        
        Task::factory()->count(2)->pending()->create(['user_id' => $this->user->id]);
        Task::factory()->count(3)->completed()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks?status=pending');

        $response->assertStatus(200);
        // When filtering by status=pending, meta should show counts for filtered results
        $this->assertEquals(2, $response->json('meta.total'));
        $this->assertEquals(2, $response->json('meta.pending'));
        $this->assertEquals(0, $response->json('meta.completed'));
    }

    /** @test */
    public function user_can_filter_tasks_by_priority()
    {
        Sanctum::actingAs($this->user);
        
        Task::factory()->count(2)->highPriority()->create(['user_id' => $this->user->id]);
        Task::factory()->count(3)->mediumPriority()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks?priority=high');

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('meta.total'));
        $this->assertEquals(2, $response->json('meta.high_priority'));
    }

    /** @test */
    public function user_can_search_tasks()
    {
        Sanctum::actingAs($this->user);
        
        Task::factory()->create([
            'title' => 'Important meeting',
            'user_id' => $this->user->id
        ]);
        Task::factory()->create([
            'title' => 'Regular task',
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson('/api/tasks?search=meeting');

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('meta.total'));
        $this->assertEquals('Important meeting', $response->json('data.0.title'));
    }

    /** @test */
    public function user_can_create_task()
    {
        Sanctum::actingAs($this->user);
        
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task description',
            'priority' => 'high'
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Task created successfully.',
                    'data' => [
                        'title' => 'New Task',
                        'description' => 'Task description',
                        'priority' => 'high',
                        'status' => 'pending'
                    ]
                ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $this->user->id,
            'order' => 1
        ]);
    }

    /** @test */
    public function user_cannot_create_task_without_title()
    {
        Sanctum::actingAs($this->user);
        
        $response = $this->postJson('/api/tasks', [
            'description' => 'Task description'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function user_can_view_specific_task()
    {
        Sanctum::actingAs($this->user);
        
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'id' => $task->id,
                        'title' => $task->title
                    ]
                ]);
    }

    /** @test */
    public function user_cannot_view_other_users_task()
    {
        Sanctum::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_update_task()
    {
        Sanctum::actingAs($this->user);
        
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'priority' => 'high'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Task updated successfully.',
                    'data' => [
                        'title' => 'Updated Task',
                        'priority' => 'high'
                    ]
                ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'priority' => 'high'
        ]);
    }

    /** @test */
    public function user_cannot_update_other_users_task()
    {
        Sanctum::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Task'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_task()
    {
        Sanctum::actingAs($this->user);
        
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Task deleted successfully.'
                ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_cannot_delete_other_users_task()
    {
        Sanctum::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_toggle_task_status()
    {
        Sanctum::actingAs($this->user);
        
        $task = Task::factory()->pending()->create(['user_id' => $this->user->id]);

        $response = $this->patchJson("/api/tasks/{$task->id}/toggle-status");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Task status updated successfully.',
                    'data' => [
                        'status' => 'completed'
                    ]
                ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function user_can_reorder_tasks()
    {
        Sanctum::actingAs($this->user);
        
        $task1 = Task::factory()->create(['user_id' => $this->user->id, 'order' => 1]);
        $task2 = Task::factory()->create(['user_id' => $this->user->id, 'order' => 2]);
        $task3 = Task::factory()->create(['user_id' => $this->user->id, 'order' => 3]);

        $response = $this->postJson('/api/tasks/reorder', [
            'tasks' => [
                ['id' => $task3->id, 'order' => 1],
                ['id' => $task1->id, 'order' => 2],
                ['id' => $task2->id, 'order' => 3],
            ]
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Tasks reordered successfully.'
                ]);

        $this->assertDatabaseHas('tasks', ['id' => $task3->id, 'order' => 1]);
        $this->assertDatabaseHas('tasks', ['id' => $task1->id, 'order' => 2]);
        $this->assertDatabaseHas('tasks', ['id' => $task2->id, 'order' => 3]);
    }

    /** @test */
    public function user_cannot_reorder_other_users_tasks()
    {
        Sanctum::actingAs($this->user);
        
        $otherUser = User::factory()->create();
        $otherTask = Task::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->postJson('/api/tasks/reorder', [
            'tasks' => [
                ['id' => $otherTask->id, 'order' => 1],
            ]
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_view_task_statistics()
    {
        Sanctum::actingAs($this->user);
        
        // Create specific tasks with known statuses and priorities
        Task::factory()->pending()->lowPriority()->create(['user_id' => $this->user->id]);
        Task::factory()->pending()->mediumPriority()->create(['user_id' => $this->user->id]);
        Task::factory()->completed()->highPriority()->create(['user_id' => $this->user->id]);
        Task::factory()->completed()->lowPriority()->create(['user_id' => $this->user->id]);
        Task::factory()->completed()->mediumPriority()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks/statistics');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'total', 'pending', 'completed', 'low_priority', 
                        'medium_priority', 'high_priority', 'completion_rate'
                    ]
                ]);

        // Verify the counts are correct
        $this->assertEquals(5, $response->json('data.total'));
        $this->assertEquals(2, $response->json('data.pending'));
        $this->assertEquals(3, $response->json('data.completed'));
        $this->assertEquals(60.0, $response->json('data.completion_rate'));
    }

    /** @test */
    public function guest_cannot_access_task_endpoints()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);

        $response = $this->postJson('/api/tasks', []);
        $response->assertStatus(401);

        $response = $this->getJson('/api/tasks/1');
        $response->assertStatus(401);
    }

    /** @test */
    public function task_order_is_automatically_assigned_on_creation()
    {
        Sanctum::actingAs($this->user);
        
        // Create tasks with existing orders
        Task::factory()->create(['user_id' => $this->user->id, 'order' => 5]);
        Task::factory()->create(['user_id' => $this->user->id, 'order' => 10]);

        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task'
        ]);

        $response->assertStatus(201);
        
        // New task should get order 11 (highest + 1)
        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'order' => 11
        ]);
    }

    /** @test */
    public function task_order_is_reordered_after_deletion()
    {
        Sanctum::actingAs($this->user);
        
        $task1 = Task::factory()->create(['user_id' => $this->user->id, 'order' => 1]);
        $task2 = Task::factory()->create(['user_id' => $this->user->id, 'order' => 2]);
        $task3 = Task::factory()->create(['user_id' => $this->user->id, 'order' => 3]);

        // Delete the middle task
        $this->deleteJson("/api/tasks/{$task2->id}");

        // Remaining tasks should be reordered
        $this->assertDatabaseHas('tasks', ['id' => $task1->id, 'order' => 1]);
        $this->assertDatabaseHas('tasks', ['id' => $task3->id, 'order' => 2]);
    }

    /** @test */
    public function debug_scope_application()
    {
        Sanctum::actingAs($this->user);
        
        // Create tasks with specific statuses
        $pendingTask = Task::factory()->pending()->create(['user_id' => $this->user->id]);
        $completedTask = Task::factory()->completed()->create(['user_id' => $this->user->id]);
        
        // Debug: Check what was actually created
        $this->assertEquals('pending', $pendingTask->status);
        $this->assertEquals('completed', $completedTask->status);
        
        // Debug: Check the relationship query
        $pendingTasks = $this->user->tasks()->status('pending')->get();
        $this->assertEquals(1, $pendingTasks->count());
        $this->assertEquals('pending', $pendingTasks->first()->status);
        
        // Debug: Check the controller response
        $response = $this->getJson('/api/tasks?status=pending');
        $response->assertStatus(200);
        
        // Log the response for debugging
        Log::info('Filtered response:', $response->json());
        
        $this->assertEquals(1, $response->json('meta.total'));
        $this->assertEquals(1, $response->json('meta.pending'));
    }
}
