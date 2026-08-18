<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_register_page_loads(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_tasks_page_is_accessible_to_guests(): void
    {
        $response = $this->get('/tasks');

        $response->assertStatus(200);
    }

    public function test_home_page_redirects_to_login_when_guest(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(302);
    }

    public function test_tasks_page_loads_when_authenticated(): void
    {
        $user = User::create([
            'first_name' => 'Auth',
            'last_name' => 'User',
            'email' => 'auth@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertStatus(200);
    }

    public function test_home_page_loads_when_authenticated(): void
    {
        $user = User::create([
            'first_name' => 'Home',
            'last_name' => 'User',
            'email' => 'home@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
    }

    public function test_can_register_new_user(): void
    {
        $response = $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }

    public function test_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'first_name' => 'Login',
            'last_name' => 'Test',
            'email' => 'login@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    public function test_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(302);
        $this->assertGuest();
    }
}
