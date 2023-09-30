<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\PublicMessage::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\User::class),
        'project_id' => factory(App\Project::class),
        'comment' => $faker->sentence,
    ];
});