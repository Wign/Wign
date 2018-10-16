<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $user = App\User::orderByRaw('RAND()')->first();

    return [

        'video_id' => function () {
            return factory( App\Video::class )->create()->id;
        },
        'description_id' => function () {
            return factory( App\Description::class )->create()->id;
        },

        'user_id' => $user->id,
    ];
});
