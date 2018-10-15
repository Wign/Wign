<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'video_id'             => function () {
            return factory( App\Video::class )->create()->id;
        },
        'description_id'             => function () {
            return factory( App\Description::class )->create()->id;
        }
    ];
});
