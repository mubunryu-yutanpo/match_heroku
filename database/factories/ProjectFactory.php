<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'type' => function () {
            return factory(App\Type::class)->create()->id;
        },
        'title' => $faker->sentence,
        'upperPrice' => $faker->numberBetween(1000, 5000),
        'lowerPrice' => $faker->numberBetween(500, 1000),
        'thumbnail' => '/uploads/thumbnail-default.png',
        'content' => $faker->paragraph,
    ];
});