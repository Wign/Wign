<?php

use Faker\Generator as Faker;

$factory->define(App\Remotion::class, function (Faker $faker) {
    $qcv = \App\QCV::without('remotions')->inRandomOrder()->first();
    $user = \App\User::inRandomOrder()->first();
    $promotion = $qcv->rank == 0 ? true : ($qcv->rank == config('global.rank_max') ? false : $faker->boolean);

    return [
        'qcv_id' => $qcv->id,
        'user_id' => $user->id,
        'promotion' => $promotion,
    ];
});
