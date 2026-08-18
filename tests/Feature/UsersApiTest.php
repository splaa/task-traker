<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_users(): void
    {
        User::create([
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
        ]);
        User::create([
            'first_name' => 'Bob',
            'last_name' => 'Jones',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_create_user(): void
    {
        $response = $this->postJson('/api/users', [
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => 'newuser@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'newuser@example.com']);
    }

    public function test_can_show_user(): void
    {
        $user = User::create([
            'first_name' => 'Show',
            'last_name' => 'Me',
            'email' => 'show@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->getJson("/api/users/{$user->user_id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => 'show@example.com']);
    }

    public function test_can_update_user(): void
    {
        $user = User::create([
            'first_name' => 'Old',
            'last_name' => 'Name',
            'email' => 'old@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->putJson("/api/users/{$user->user_id}", [
            'first_name' => 'New',
            'last_name' => 'Name',
        ]);

        $response->assertStatus(200);
    }

    public function test_can_delete_user(): void
    {
        $user = User::create([
            'first_name' => 'Delete',
            'last_name' => 'Me',
            'email' => 'delete@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->deleteJson("/api/users/{$user->user_id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['user_id' => $user->user_id]);
    }

    public function test_show_nonexistent_user_returns_404(): void
    {
        $response = $this->getJson('/api/users/999');
        $response->assertStatus(404);
    }
}
