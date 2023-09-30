<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Chat::class, function (Faker $faker) {
    return [
        'user1_id' => factory(App\User::class),
        'user2_id' => factory(App\User::class),
    ];
});