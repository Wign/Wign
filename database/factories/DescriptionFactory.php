<?php

use Faker\Generator as Faker;

$factory->define(App\Description::class, function (Faker $faker) {
    $users = \App\User::withTrashed()->inRandomOrder();
    $u1 = $users->first();
    $u2 = random_int(0, 4) == 0 ? $users->skip(1)->first() : $u1;
    $text = $faker->textWithHashtag . $faker->sentence();

    return [
        'text' => $text,
        'creator_id' => $u1->id,
        'editor_id' => $u2->id,
    ];
});
