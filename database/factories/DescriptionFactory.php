<?php

use Faker\Generator as Faker;

$factory->define(App\Description::class, function (Faker $faker) {
    $user = \App\User::withTrashed()->take(random_int(0, 100))->first();
    $text = $faker->textWithHashtag . $faker->sentence();

    return [
        'text' => $text,

        'user_id' => $user->id,
    ];
});
