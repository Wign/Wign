<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
        //Model::unguard();

		$this->call( UsersTableSeeder::class );
        $this->call( WordsTableSeeder::class );
        //$this->call( PostTableSeeder::class );
		//$this->call( LikeTableSeeder::class );
		//$this->call( RequestWordTableSeeder::class );
        //$this->call( AliasTableSeeder::class );
        //$this->call( ReviewTableSeeder::class );
        //$this->call( RemotionTableSeeder::class );

        //Model::reguard();
	}
}