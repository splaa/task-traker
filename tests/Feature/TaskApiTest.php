<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'user_id' => 1,
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_can_list_tasks(): void
    {
        Task::create(['title' => 'Task 1', 'description' => 'Description 1', 'status' => 'View']);
        Task::create(['title' => 'Task 2', 'description' => 'Description 2', 'status' => 'In Progress']);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['title' => 'Task 1'])
            ->assertJsonFragment(['title' => 'Task 2']);
    }

    public function test_can_create_task(): void
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'New Description',
            'status' => 'View',
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Task'])
            ->assertJsonFragment(['description' => 'New Description'])
            ->assertJsonFragment(['status' => 'View']);

        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_can_show_task(): void
    {
        $task = Task::create([
            'title' => 'Single Task',
            'description' => 'Single Description',
            'status' => 'Done',
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Single Task'])
            ->assertJsonFragment(['status' => 'Done']);
    }

    public function test_can_update_task(): void
    {
        $task = Task::create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'status' => 'View',
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'In Progress',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title'])
            ->assertJsonFragment(['status' => 'In Progress']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::create([
            'title' => 'Deletable Task',
            'description' => 'Will be deleted',
            'status' => 'View',
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_can_update_task_status(): void
    {
        $task = Task::create([
            'title' => 'Status Task',
            'description' => 'Status change',
            'status' => 'View',
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}/status/In Progress");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'In Progress']);
    }

    public function test_can_update_task_status_to_done(): void
    {
        $task = Task::create([
            'title' => 'Done Task',
            'description' => 'Will be done',
            'status' => 'View',
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}/status/Done");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => 'Done']);
    }

    public function test_can_filter_tasks_by_id_ascending(): void
    {
        Task::create(['title' => 'A', 'description' => 'Desc A', 'status' => 'View']);
        Task::create(['title' => 'B', 'description' => 'Desc B', 'status' => 'View']);

        $response = $this->getJson('/api/tasks/filter/id');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_filter_tasks_by_status(): void
    {
        Task::create(['title' => 'View Task', 'description' => 'View', 'status' => 'View']);
        Task::create(['title' => 'Progress Task', 'description' => 'Progress', 'status' => 'In Progress']);

        $response = $this->getJson('/api/tasks/filter/status');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_filter_tasks_by_id_descending(): void
    {
        Task::create(['title' => 'First', 'description' => 'First', 'status' => 'View']);
        Task::create(['title' => 'Second', 'description' => 'Second', 'status' => 'View']);

        $response = $this->getJson('/api/tasks/filter/id/desc');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(2, $data);
        // First item should have a higher id than the second (descending order)
        $ids = array_column($data, 'id');
        $this->assertEquals([2, 1], $ids);
    }

    public function test_returns_404_for_nonexistent_task(): void
    {
        $response = $this->getJson('/api/tasks/999');

        $response->assertStatus(404);
    }

    public function test_can_update_task_title_only(): void
    {
        $task = Task::create([
            'title' => 'Old Title',
            'description' => 'Keep Description',
            'status' => 'View',
        ]);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'New Title',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'New Title'])
            ->assertJsonFragment(['description' => 'Keep Description']);
    }
}
