<?php

use Illuminate\Database\Seeder;

class RequestWordTableSeeder extends Seeder {

	static $count = 0;

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
/*		factory( \App\RequestWord::class, 75 )->create()->each( function ( $rw ) {
			if(static::$count % 10 == 0) {
				factory( \App\RequestWord::class, rand(1,5) )->create(['word_id' => $rw->word_id]);
			}
			static::$count++;
        } );*/
	}
}
