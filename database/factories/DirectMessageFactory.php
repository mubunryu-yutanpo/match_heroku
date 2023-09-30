<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\DirectMessage::class, function (Faker $faker) {
    return [
        'sender_id' => factory(App\User::class),
        'chat_id' => factory(App\Chat::class),
        'comment' => $faker->sentence,
    ];
});