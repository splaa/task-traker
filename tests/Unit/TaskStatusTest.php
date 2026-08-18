<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Infrastructure\Interfaces\Tasks\TaskStatusInterface;
use PHPUnit\Framework\TestCase;

class TaskStatusTest extends TestCase
{
    public function test_status_constants_are_defined(): void
    {
        $this->assertEquals('View', TaskStatusInterface::TASK_STATUS_VIEW);
        $this->assertEquals('In Progress', TaskStatusInterface::TASK_STATUS_IN_PROGRESS);
        $this->assertEquals('Done', TaskStatusInterface::TASK_STATUS_DONE);
    }

    public function test_status_array_contains_all_statuses(): void
    {
        $expected = ['View', 'In Progress', 'Done'];
        $this->assertEquals($expected, TaskStatusInterface::STATUS);
    }
}
