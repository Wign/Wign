<?php

use Faker\Generator as Faker;

$factory->define(\App\Blacklist::class, function (Faker $faker) {
    return [
        'ip' => $faker->ipv4,
	    'reason' => $faker->sentence
    ];
});
