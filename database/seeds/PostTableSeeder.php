<?php

use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder {

	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Post::class, 1000)->create()->each(function($u) {
            $u->ils()->save(factory(App\Il::class)->make());
            $u->videos()->save(factory(\App\Video::class)->make(['user_id' => $u->user_id]));
            $u->descriptions()->save(factory(\App\Description::class)->make(['user_id' => $u->user_id]));
        });
    }
}
