<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(\App\Vote::class, function (Faker $faker) {
    return [
	    'ip' => $faker->ipv4,
        'sign_id' => function() {
    	    return \App\Sign::all()->random();
        }
    ];
});
