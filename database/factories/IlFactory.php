<?php

use Faker\Generator as Faker;

$factory->define(\App\Il::class, function (Faker $faker) {
    $faker->addProvider(new App\Helpers\FakerProvider($faker));

    return [
        'rank' => $faker->numberBetween(0, config('global.rank_max')),
    ];
});
