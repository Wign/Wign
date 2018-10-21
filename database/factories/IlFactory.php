<?php

use Faker\Generator as Faker;

$factory->define(\App\Il::class, function (Faker $faker) {
    $faker->addProvider(new App\Helpers\FakerProvider($faker));
    $rank = $faker->numberBetween(1, config('global.rank_max'));

    return [
        'rank' => $rank,
    ];
});
