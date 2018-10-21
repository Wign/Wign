<?php

use Faker\Generator as Faker;

$factory->define(App\Review::class, function (Faker $faker) {
    $il = \App\IL::without('reviews')->inRandomOrder()->first();
    $user = \App\User::inRandomOrder()->first();

    return [
        'il_id' => $il->id,
        'user_id' => $user->id,
    ];
});
