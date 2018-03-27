<?php

use Faker\Generator as Faker;

$factory->define( \App\RequestWord::class, function ( Faker $faker ) {
	$faker->addProvider( new App\Helpers\FakerProvider( $faker ) );

	return [
		'word_id' => function() {
			return factory(\App\Word::class)->create()->id;
		},
		'ip'      => $faker->ipv4,
	];
} );
