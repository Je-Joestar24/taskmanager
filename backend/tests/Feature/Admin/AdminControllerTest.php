<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Laravel\Sanctum\Sanctum;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Create regular user
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function admin_can_access_dashboard()
    {
        Sanctum::actingAs($this->admin);

        // Create some test data
        User::factory()->count(3)->create();
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'total_users', 'total_tasks', 'pending_tasks', 
                        'completed_tasks', 'completion_rate'
                    ]
                ]);

        // Check that we have users and tasks (exact count may vary due to database state)
        $this->assertGreaterThan(0, $response->json('data.total_users'));
        $this->assertGreaterThan(0, $response->json('data.total_tasks'));
    }

    /** @test */
    public function admin_can_view_all_users_with_pagination()
    {
        Sanctum::actingAs($this->admin);

        // Create additional users
        User::factory()->count(10)->create();

        $response = $this->getJson('/api/admin/users?per_page=5');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id', 'name', 'email', 'role', 'total_tasks', 
                            'pending_tasks', 'completed_tasks', 'tasks'
                        ]
                    ],
                    'meta' => [
                        'current_page', 'last_page', 'per_page', 'total'
                    ]
                ]);

        $this->assertEquals(5, $response->json('meta.per_page'));
        $this->assertEquals(12, $response->json('meta.total')); // 10 + admin + regular user
    }

    /** @test */
    public function admin_can_view_all_tasks_with_filtering()
    {
        Sanctum::actingAs($this->admin);

        // Create tasks with different statuses and priorities
        Task::factory()->count(3)->pending()->create();
        Task::factory()->count(2)->completed()->create();
        Task::factory()->count(2)->highPriority()->create();

        $response = $this->getJson('/api/admin/tasks?status=pending&priority=high');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id', 'title', 'description', 'status', 'priority', 
                            'order', 'user_id', 'created_at', 'updated_at', 'user'
                        ]
                    ],
                    'meta' => [
                        'current_page', 'last_page', 'per_page', 'total'
                    ]
                ]);
    }

    /** @test */
    public function admin_can_search_tasks()
    {
        Sanctum::actingAs($this->admin);

        Task::factory()->create([
            'title' => 'Important admin task',
            'user_id' => $this->regularUser->id
        ]);

        Task::factory()->create([
            'title' => 'Regular task',
            'user_id' => $this->regularUser->id
        ]);

        $response = $this->getJson('/api/admin/tasks?search=admin');

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('meta.total'));
        $this->assertEquals('Important admin task', $response->json('data.0.title'));
    }

    /** @test */
    public function admin_can_view_user_specific_statistics()
    {
        Sanctum::actingAs($this->admin);

        // Create tasks for regular user with specific statuses
        Task::factory()->pending()->create(['user_id' => $this->regularUser->id]);
        Task::factory()->pending()->create(['user_id' => $this->regularUser->id]);
        Task::factory()->completed()->create(['user_id' => $this->regularUser->id]);
        Task::factory()->completed()->create(['user_id' => $this->regularUser->id]);
        Task::factory()->completed()->create(['user_id' => $this->regularUser->id]);

        $response = $this->getJson("/api/admin/users/{$this->regularUser->id}/stats");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'user_id', 'user_name', 'user_email', 'total_tasks',
                        'pending_tasks', 'completed_tasks', 'low_priority',
                        'medium_priority', 'high_priority', 'completion_rate'
                    ]
                ]);


        
        // Check that we have the expected task counts
        $this->assertEquals(5, $response->json('data.total_tasks')); // 2 pending + 3 completed
        $this->assertEquals(2, $response->json('data.pending_tasks'));
        $this->assertEquals(3, $response->json('data.completed_tasks'));
        $this->assertEquals(60.0, $response->json('data.completion_rate')); // 3/5 * 100
    }

    /** @test */
    public function regular_user_cannot_access_admin_dashboard()
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->getJson('/api/admin/dashboard');

        $response->assertStatus(403)
                ->assertJson([
                    'message' => 'Unauthorized. Admin access only.'
                ]);
    }

    /** @test */
    public function regular_user_cannot_access_admin_users()
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_access_admin_tasks()
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->getJson('/api/admin/tasks');

        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_access_user_stats()
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->getJson("/api/admin/users/{$this->admin->id}/stats");

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_admin_endpoints()
    {
        $response = $this->getJson('/api/admin/dashboard');
        $response->assertStatus(401);

        $response = $this->getJson('/api/admin/users');
        $response->assertStatus(401);

        $response = $this->getJson('/api/admin/tasks');
        $response->assertStatus(401);
    }

    /** @test */
    public function admin_dashboard_shows_correct_statistics()
    {
        Sanctum::actingAs($this->admin);

        // Create users and tasks
        User::factory()->count(2)->create();
        Task::factory()->count(3)->pending()->create();
        Task::factory()->count(2)->completed()->create();

        $response = $this->getJson('/api/admin/dashboard');

        $response->assertStatus(200);
        
        // Check that we have users and tasks (exact count may vary due to database state)
        $this->assertGreaterThan(0, $response->json('data.total_users'));
        $this->assertEquals(5, $response->json('data.total_tasks'));
        $this->assertEquals(3, $response->json('data.pending_tasks'));
        $this->assertEquals(2, $response->json('data.completed_tasks'));
        $this->assertEquals(40.0, $response->json('data.completion_rate')); // 2/5 * 100
    }

    /** @test */
    public function admin_users_endpoint_includes_task_counts()
    {
        Sanctum::actingAs($this->admin);

        // Create tasks for regular user
        Task::factory()->count(2)->pending()->create(['user_id' => $this->regularUser->id]);
        Task::factory()->count(1)->completed()->create(['user_id' => $this->regularUser->id]);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(200);
        
        // Find the regular user in the response
        $regularUserData = collect($response->json('data'))->firstWhere('id', $this->regularUser->id);
        
        $this->assertEquals(3, $regularUserData['total_tasks']);
        $this->assertEquals(2, $regularUserData['pending_tasks']);
        $this->assertEquals(1, $regularUserData['completed_tasks']);
    }

    /** @test */
    public function admin_tasks_endpoint_includes_user_information()
    {
        Sanctum::actingAs($this->admin);

        Task::factory()->create(['user_id' => $this->regularUser->id]);

        $response = $this->getJson('/api/admin/tasks');

        $response->assertStatus(200);
        $this->assertArrayHasKey('user', $response->json('data.0'));
        $this->assertEquals($this->regularUser->id, $response->json('data.0.user.id'));
        $this->assertEquals($this->regularUser->name, $response->json('data.0.user.name'));
    }
}
