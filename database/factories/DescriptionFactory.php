<?php

use Faker\Generator as Faker;

$factory->define(App\Description::class, function (Faker $faker) {
    $user = App\User::inRandomOrder()->first();

    return [
        'text' => $faker->sentence(),
        // 'text' => $faker->textWithHashtag,

        'user_id' => $user->id,
    ];
});
