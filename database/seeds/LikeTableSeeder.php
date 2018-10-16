<?php

use Illuminate\Database\Seeder;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Like::class, 1000)->create()->each(function($u) {
            $u->ils()->save(factory(App\Il::class)->make());
        });
    }
}
