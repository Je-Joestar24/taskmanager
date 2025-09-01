<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'John Doe',
            'email'                 => 'johndoe@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'access_token',
                     'token_type',
                     'user' => ['id', 'name', 'email'],
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'role'  => 'user',
        ]);
    }

    /** @test */
    public function registration_fails_with_duplicate_email()
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'name'                  => 'Jane Doe',
            'email'                 => 'duplicate@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => 'client',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function registration_fails_with_unconfirmed_password()
    {
        $response = $this->postJson('/api/register', [
            'name'                  => 'Sam Smith',
            'email'                 => 'samsmith@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'wrongpassword',
            'role'                  => 'client',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }
}
