<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Notification::class, function (Faker $faker) {
    return [
        'receiver_id' => factory(App\User::class),
        'sender_id' => factory(App\User::class),
        'chat_id' => factory(App\Chat::class),
        'read' => $faker->boolean,
        'content' => $faker->sentence,
    ];
});