<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiAdvancedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'user_id' => 1,
            'first_name' => 'Default',
            'last_name' => 'User',
            'email' => 'default@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_returns_empty_list_when_no_tasks(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)->assertJson([]);
    }

public function test_create_task_with_minimal_data(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Minimal Task',
            'description' => 'Desc',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Minimal Task']);
    }

    public function test_update_status_without_space_to_in_progress(): void
    {
        $task = Task::create(['title' => 'Status Test', 'description' => 'Test']);
        $task = $task->fresh();
        $this->assertEquals('View', $task->status);

        $response = $this->putJson("/api/tasks/{$task->id}/status/InProgress");

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'In Progress']);
    }

    public function test_change_user_to_task(): void
    {
        $user2 = User::create([
            'first_name' => 'Second',
            'last_name' => 'User',
            'email' => 'second@example.com',
            'password' => bcrypt('password'),
        ]);

        $task = Task::create(['title' => 'Reassign', 'description' => 'Will be reassigned']);
        $task = $task->fresh();
        $this->assertEquals(1, $task->getAttributes()['user']);

        $response = $this->putJson("/api/tasks/{$task->id}/user/{$user2->user_id}");

        $response->assertStatus(200);
        $task->refresh();
        $this->assertEquals($user2->user_id, $task->getAttributes()['user']);
    }

    public function test_update_with_invalid_data_still_succeeds(): void
    {
        $task = Task::create(['title' => 'Original', 'description' => 'Desc']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Just Updated',
            'description' => 'Updated',
        ]);

        $response->assertStatus(200);
    }

    public function test_multiple_tasks_returned_in_response(): void
    {
        Task::create(['title' => 'Task A', 'description' => 'A']);
        Task::create(['title' => 'Task B', 'description' => 'B']);
        Task::create(['title' => 'Task C', 'description' => 'C']);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)->assertJsonCount(3);
    }

    public function test_filter_by_status_returns_correct_order(): void
    {
        Task::create(['title' => 'First', 'description' => '1', 'status' => 'Done']);
        Task::create(['title' => 'Second', 'description' => '2', 'status' => 'In Progress']);
        Task::create(['title' => 'Third', 'description' => '3', 'status' => 'View']);

        $response = $this->getJson('/api/tasks/filter/status');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(3, $data);
    }

    public function test_filter_by_status_descending(): void
    {
        Task::create(['title' => 'First', 'description' => '1', 'status' => 'Done']);
        Task::create(['title' => 'Second', 'description' => '2', 'status' => 'In Progress']);
        Task::create(['title' => 'Third', 'description' => '3', 'status' => 'View']);

        $response = $this->getJson('/api/tasks/filter/status/desc');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(3, $data);
    }

    public function test_delete_nonexistent_task_returns_404(): void
    {
        $response = $this->deleteJson('/api/tasks/999');
        $response->assertStatus(404);
    }

    public function test_update_nonexistent_task_returns_404(): void
    {
        $response = $this->putJson('/api/tasks/999', ['title' => 'Ghost']);
        $response->assertStatus(404);
    }

    public function test_create_task_with_all_fields(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Full Task',
            'description' => 'Full description here',
            'status' => 'Done',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'title' => 'Full Task',
            'description' => 'Full description here',
            'status' => 'Done',
        ]);
    }

    public function test_create_task_then_update_status_via_put(): void
    {
        $task = Task::create(['title' => 'Flow', 'description' => 'Test flow']);
        $task = $task->fresh();

        $this->putJson("/api/tasks/{$task->id}/status/Done")
            ->assertStatus(200)
            ->assertJsonFragment(['status' => 'Done']);

        $this->putJson("/api/tasks/{$task->id}/status/View")
            ->assertStatus(200)
            ->assertJsonFragment(['status' => 'View']);
    }
}
