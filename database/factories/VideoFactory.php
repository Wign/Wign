<?php

use Faker\Generator as Faker;

$factory->define(App\Video::class, function (Faker $faker) {
    $faker->addProvider( new App\Helpers\FakerProvider( $faker ) );
    $url = "//www.cameratag.com/";
    $videoUUID = "v-" . $faker->uuid;
    $user = \App\User::withTrashed()->inRandomOrder()->first();

    return [
        'video_uuid'          => $videoUUID,
        'camera_uuid'         => "c-" . $faker->uuid,
        'video_url'           => $url . "videos/" . $videoUUID . "/qvga/mp4.mp4",
        'thumbnail_url'       => $url . "assets/" . $videoUUID . "/vga_thumb.png",
        'small_thumbnail_url' => $url . "assets/" . $videoUUID . "/qvga_thumb.jpg",
        'playings'            => rand(0,10000),

        'user_id'             => $user->id,
    ];
});
