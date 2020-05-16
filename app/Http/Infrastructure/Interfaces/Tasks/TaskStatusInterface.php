<?php


namespace App\Http\Infrastructure\Interfaces\Tasks;


interface TaskStatusInterface
{
    public const TASK_STATUS_VIEW = 'View';
    public const TASK_STATUS_IN_PROGRESS = 'In Progress';
    public const TASK_STATUS_DONE = 'Done';


    public const STATUS = [
        self::TASK_STATUS_VIEW,
        self::TASK_STATUS_IN_PROGRESS,
        self::TASK_STATUS_DONE,
    ];
}
