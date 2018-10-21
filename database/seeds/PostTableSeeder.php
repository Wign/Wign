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
            $Il = factory(App\Il::class)->make();
            $video = factory(\App\Video::class)->make(['user_id' => $u->user_id]);
            $desc = factory(\App\Description::class)->make(['user_id' => $u->user_id]);
            if (random_int(0, 19) == 0 && \App\Word::count() !== 0)   {
                $word = \App\Word::inRandomOrder()->first();
            } else {
                $word = factory(\App\Word::class)->create(['user_id' => $u->user_id]);
            }

            $u->ils()->save($Il);
            $u->videos()->save($video);
            $u->descriptions()->save($desc);
            $u->words()->attach($word, ['user_id' => $u->user_id]);
        });
    }
}
