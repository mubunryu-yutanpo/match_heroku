<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Apply::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class),
        'project_id' => factory(App\Project::class),
    ];
});