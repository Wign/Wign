<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(\App\Word::class, function (Faker $faker) {
	$faker->addProvider(new App\Helpers\FakerProvider($faker));

    return [
        'word' => $faker->unique()->wignWords(),
    ];
});