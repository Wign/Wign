<?php

use Illuminate\Database\Seeder;

class BlacklistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Blacklist::class, 10)->create();
    }
}
