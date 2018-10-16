<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $faker->addProvider(new App\Helpers\FakerProvider($faker));

    $user = \App\User::inRandomOrder()->first();

    return [
        'user_id' => $user->id,
    ];
});
