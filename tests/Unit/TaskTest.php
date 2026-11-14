<?php

namespace Tests\Unit;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesDefaultTaskUser;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    use CreatesDefaultTaskUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createDefaultTaskUser();
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
        $this->assertEquals($this->defaultTaskUser->getKey(), $task->getAttributes()['user']);
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

    public function test_multiple_tasks_resolve_their_assigned_user_when_ids_differ(): void
    {
        $owner = User::create([
            'first_name' => 'Assigned', 'last_name' => 'Owner',
            'email' => 'assigned@example.com', 'password' => bcrypt('password'),
        ]);
        $taskIds = [];
        foreach (['First', 'Second'] as $title) {
            $task = new Task(['title' => $title, 'description' => 'Same assigned user']);
            $task->user = $owner->getKey();
            $task->save();
            $taskIds[] = $task->getKey();
            $this->assertEquals($owner->getKey(), $task->fresh()->user()->first()?->getKey());
        }

        // Two distinct task IDs cannot both equal this one user's primary key.
        foreach (Task::with('user')->whereKey($taskIds)->get() as $task) {
            $this->assertEquals($owner->getKey(), $task->getRelation('user')?->getKey());
        }
    }
}
