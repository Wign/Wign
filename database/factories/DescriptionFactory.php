<?php

use Faker\Generator as Faker;

$factory->define(App\description::class, function (Faker $faker) {
    return [
        'text' => $faker->sentence()
        // 'text' => $faker->textWithHashtag,
    ];
});
