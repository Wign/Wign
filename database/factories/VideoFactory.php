<?php

use Faker\Generator as Faker;

$factory->define(App\Video::class, function (Faker $faker) {
    $faker->addProvider( new App\Helpers\FakerProvider( $faker ) );
    $url = $faker->url;
    $user = \App\User::withTrashed()->take(random_int(0, \App\User::count()))->first();

    return [
        'video_uuid'          => "v-" . $faker->uuid,
        'camera_uuid'         => "c-" . $faker->uuid,
        'video_url'           => $url . "mp4.mp4",
        'thumbnail_url'       => $url . "vga_thumb.png",
        'small_thumbnail_url' => $url . "qvga_thumb.jpg",
        'playings'            => rand(0,10000),

        'user_id'             => $user->id,
    ];
});
