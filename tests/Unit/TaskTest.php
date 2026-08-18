<?php

namespace Tests\Unit;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
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

    public function test_task_has_fillable_attributes(): void
    {
        $task = new Task();
        $this->assertEquals(['title', 'description', 'status'], $task->getFillable());
    }

    public function test_task_can_be_created_with_defaults(): void
    {
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'Test Description',
        ]);

        $task = $task->fresh();
        $this->assertNotNull($task->id);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals('View', $task->status);
    }

    public function test_task_belongs_to_user(): void
    {
        $task = Task::create([
            'title' => 'User Task',
            'description' => 'User task description',
        ]);

        $task = $task->fresh();
        $this->assertEquals(1, $task->getAttributes()['user']);
        $this->assertInstanceOf(User::class, $task->user()->first());
    }

    public function test_task_can_have_different_statuses(): void
    {
        $viewTask = Task::create(['title' => 'View', 'description' => 'View task', 'status' => 'View']);
        $progressTask = Task::create(['title' => 'Progress', 'description' => 'Progress task', 'status' => 'In Progress']);
        $doneTask = Task::create(['title' => 'Done', 'description' => 'Done task', 'status' => 'Done']);

        $this->assertEquals('View', $viewTask->status);
        $this->assertEquals('In Progress', $progressTask->status);
        $this->assertEquals('Done', $doneTask->status);
    }
}
