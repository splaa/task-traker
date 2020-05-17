<?php

/** @var Factory $factory */

use App\Http\Infrastructure\Interfaces\Tasks\TaskStatusInterface;
use App\Task;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Task::class, static function (Faker $faker) {

    return [
        'title' => $faker->title,
        'description' => $faker->realText(50),
        'status' => TaskStatusInterface::STATUS[random_int(0,2)],
    ];
});
