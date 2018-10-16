<?php

use Faker\Generator as Faker;

$factory->define(App\description::class, function (Faker $faker) {
    $user = App\User::orderByRaw('RAND()')->first();

    return [
        'text' => $faker->sentence(),
        // 'text' => $faker->textWithHashtag,

        'user_id' => $user->id,
    ];
});
