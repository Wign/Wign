<?php
use Faker\Generator as Faker;

$factory->define(\App\Word::class, function (Faker $faker) {
	$faker->addProvider(new App\Helpers\FakerProvider($faker));
    $user = App\User::orderByRaw('RAND()')->first();

    return [
        'word' => $faker->unique()->wignWords(),

        //'user_id' => $user->id
    ];
});