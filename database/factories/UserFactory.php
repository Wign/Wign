<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
	static $password;
	$banned = random_int(0,19) == 0 ? true : false;
    $updated_at = $faker->dateTime();
    $tempDate = $faker->dateTime();
    if ($tempDate <= $updated_at)   {
        $created_at = $tempDate;
    } else {
        $created_at = $updated_at;
        $updated_at = $tempDate;
    }

	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = bcrypt('secret'),
		'remember_token' => str_random(10),
        'created_at' => $created_at,
        'updated_at' => $banned ? $updated_at : $created_at,
        'deleted_at' => $banned ? $updated_at : null,
        'ban_reason' => $reason = $banned ? $faker->sentence : null,
	];
});