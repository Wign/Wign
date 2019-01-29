<?php
use Faker\Generator as Faker;

$factory->define(\App\Word::class, function (Faker $faker) {
	$faker->addProvider(new App\Helpers\FakerProvider($faker));
    $users = App\User::withTrashed()->inRandomOrder();
    $u1 = $users->first();
    $u2 = random_int(0, 4) == 0 ? $users->skip(1)->first() : $u1;

    return [
        'word' => $faker->unique()->wignWords(),
        'creator_id' => $u1->id,
        'editor_id' => $u2->id,
    ];
});