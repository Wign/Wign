<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(\App\Blacklist::class, function (Faker $faker) {
    return [
        'ip' => $faker->ipv4,
	    'reason' => $faker->sentence
    ];
});
