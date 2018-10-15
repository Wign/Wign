<?php

use Faker\Generator as Faker;

$factory->define(App\Remotion::class, function (Faker $faker) {
    return [
        'promotion' => $faker->boolean
    ];
});
