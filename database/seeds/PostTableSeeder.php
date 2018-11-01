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
            // Generate previous ILs
            $il = factory(\App\Il::class)->make();
            $u->ils()->save($il);

            if (random_int(0, 4) == 0)  {
                $n = random_int(1, 10);
                for ($i = 0; $i < $n; $i++) {
                    $il = factory(App\Il::class)->make(['deleted_at' => now()]);
                    $u->ils()->save($il);
                }
            }
        });

    }
}
