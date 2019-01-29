<?php

use Faker\Generator as Faker;

$factory->define(\App\Qcv::class, function (Faker $faker) {

    $faker->addProvider(new App\Helpers\FakerProvider($faker));

    // Distribution of QCV-level that likelihood: QCV(0) < QCV(1) < ... < QCV(5)
    $rank = $faker->numberBetween(0, config('global.rank_max'));
    $challenge = $faker->numberBetween(0, config('global.rank_max'));
    $rank = $rank >= $challenge ? $challenge : $rank;

    return [
        'rank' => $rank,
    ];
});
