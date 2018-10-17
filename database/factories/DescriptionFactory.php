<?php

use Faker\Generator as Faker;

$factory->define(App\Description::class, function (Faker $faker) {
    $user = \App\User::withTrashed()->take(random_int(0, 100))->first();

    return [
        'text' => $faker->textWithHashtag . $faker->sentence(),

        'user_id' => $user->id,
    ];
});
