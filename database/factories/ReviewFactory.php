<?php

use Faker\Generator as Faker;

$factory->define(App\Review::class, function (Faker $faker) {
    $ils = \App\IL::without('reviews')->inRandomOrder()->take(2)->get();
    $user = \App\User::inRandomOrder()->first();

    return [
        'old_post_il_id' => $ils[0]->id,
        'new_post_il_id' => $ils[1]->id,
        'user_id' => $user->id,
        'decided' => false,
    ];
});
