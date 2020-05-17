<?php

use App\Task;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::namespace('Api')->group(static function () {
    Route::apiResource('tasks', 'TaskController');
    Route::apiResource('users', 'UsersController');

    Route::match(['put', 'patch'], 'tasks/{task}/status/{status_name}', 'TaskController@updateStatus')
        ->name('tasks.update.status');


    Route::get('tasks/filter/status', static function () {
        return response(Task::all()->sortBy('status'), 200);
    });
    Route::get('tasks/filter/status/desc', static function () {
        return response(Task::all()->sortByDesc('status'), 200);
    });

    Route::get('tasks/filter/id', static function () {
        return response(Task::all()->sortBy('id'), 200);
    });
    Route::get('tasks/filter/id/desc', static function () {
        return response(Task::all()->sortByDesc('id'), 200);
    });
    Route::match(['put', 'patch'], 'tasks/{task}/user/{user}', 'TaskController@changeUserToTask');

});
