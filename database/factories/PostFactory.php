<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $faker->addProvider(new App\Helpers\FakerProvider($faker));
    if (random_int(0, 19) == 0 && \App\Word::count() != 0)   {
        $word = \App\Word::inRandomOrder()->first();
    } else {
        $word = factory(\App\Word::class)->create();
    }
    $video = factory(\App\Video::class)->create();
    $description = factory(\App\Description::class)->create();

    $user = \App\User::inRandomOrder()->first();

    return [
        'word_id' => $word->id,
        'video_id' => $video->id,
        'description_id' => $description->id,
        'user_id' => $user->id,
    ];
});
