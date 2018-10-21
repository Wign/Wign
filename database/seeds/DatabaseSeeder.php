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
        $this->call( PostTableSeeder::class );
		//$this->call( RequestWordTableSeeder::class );
        //$this->call( LikeTableSeeder::class );
        //$this->call( AliasTableSeeder::class );
        $this->call( ReviewTableSeeder::class );
        //$this->call( RemotionTableSeeder::class );
        //$this->call( DeletedTableSeeder::class ); // Video + Desc + Word (husk wordlink)
        //$this->call( OldIlTableSeeder::class ); // IL

        //Model::reguard();
	}
}