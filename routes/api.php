<?php

use App\Task;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', fn(Request $request) => $request->user());

Route::apiResource('tasks', TaskController::class);
Route::apiResource('users', UsersController::class);

Route::match(['put', 'patch'], 'tasks/{task}/status/{status_name}',
    [TaskController::class, 'updateStatus'])
    ->name('tasks.update.status');

Route::get('tasks/filter/status', static fn() => response(Task::all()->sortBy('status'), 200));
Route::get('tasks/filter/status/desc', static fn() => response(Task::all()->sortByDesc('status'), 200));

Route::get('tasks/filter/id', static fn() => response(Task::all()->sortBy('id'), 200));
Route::get('tasks/filter/id/desc', static fn() => response(Task::all()->sortByDesc('id'), 200));
Route::match(['put', 'patch'], 'tasks/{task}/user/{user}',
    [TaskController::class, 'changeUserToTask']);
