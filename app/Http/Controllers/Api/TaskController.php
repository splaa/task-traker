<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Infrastructure\Interfaces\Tasks\TaskStatusInterface;
use App\Task;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Task::get(), 200);
    }



    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return Response
     */
    public function show(Task $task): Response
    {
        return response($task, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $task = Task::create($request->all());
        return response($task, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function update(Request $request, Task $task): Response
    {
        $task->update($request->all());
        return response($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return Response
     * @throws Exception
     */
    public function destroy(Task $task): Response
    {
        return response($task->delete(), 204);
    }


    /**
     * @param Task $task
     * @param $status_name
     * @return Application|ResponseFactory|Response
     *
     * API Update status
     * View Done InProgress
     * @Example
     * api/tasks/4/status/View
     * api/tasks/4/status/InProgress
     * api/tasks/4/status/Done
     */
    public function updateStatus(Task $task, $status_name)
    {

        foreach (TaskStatusInterface::STATUS as $status) {

            if ($status === $status_name) {
                $task->update(['status' => $status]);
            }
            if ('InProgress' === $status_name) {
                $task->update(['status' => TaskStatusInterface::TASK_STATUS_IN_PROGRESS]);
            }
        }
        return response($task, 200);
    }
}
