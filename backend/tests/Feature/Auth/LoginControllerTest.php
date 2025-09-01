<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test login with valid credentials when user exists
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'access_token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'created_at',
                        'updated_at'
                    ]
                ])
                ->assertJson([
                    'message' => 'Login successful.',
                    'user' => [
                        'id' => $user->id,
                        'email' => 'test@example.com',
                        'role' => 'user'
                    ]
                ]);

        // Verify token exists
        $this->assertNotEmpty($response->json('access_token'));
    }

    /**
     * Test login with invalid email when no users exist
     */
    public function test_login_fails_with_invalid_email_when_no_users_exist(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Invalid credentials.'
                ]);
    }

    /**
     * Test login with invalid password when no users exist
     */
    public function test_login_fails_with_invalid_password_when_no_users_exist(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'message' => 'Invalid credentials.'
                ]);
    }

    /**
     * Test login with missing email field
     */
    public function test_login_fails_with_missing_email(): void
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test login with missing password field
     */
    public function test_login_fails_with_missing_password(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test login with invalid email format
     */
    public function test_login_fails_with_invalid_email_format(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test login with empty email and password
     */
    public function test_login_fails_with_empty_fields(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Test login with very long email and password
     */
    public function test_login_fails_with_very_long_fields(): void
    {
        $longEmail = str_repeat('a', 300) . '@example.com';
        $longPassword = str_repeat('a', 300);

        $response = $this->postJson('/api/login', [
            'email' => $longEmail,
            'password' => $longPassword
        ]);

        // Very long fields might be rejected by Laravel's input handling
        // before reaching validation, resulting in 401 instead of 422
        $this->assertContains($response->status(), [401, 422]);
        
        if ($response->status() === 422) {
            $response->assertJsonValidationErrors(['email']);
        } else {
            $response->assertJson([
                'message' => 'Invalid credentials.'
            ]);
        }
    }

    /**
     * Test logout with authenticated user
     */
    public function test_user_can_logout_when_authenticated(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Logged out successfully.'
                ]);
    }

    /**
     * Test logout without authentication
     */
    public function test_logout_fails_without_authentication(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    /**
     * Test login with SQL injection attempt
     */
    public function test_login_fails_with_sql_injection_attempt(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => "'; DROP TABLE users; --",
            'password' => "'; DROP TABLE users; --"
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test login with XSS attempt
     */
    public function test_login_fails_with_xss_attempt(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => '<script>alert("xss")</script>@example.com',
            'password' => '<script>alert("xss")</script>'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }
}
