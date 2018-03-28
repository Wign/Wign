<?php

use Illuminate\Database\Seeder;

class SignsTableSeeder extends Seeder {

	protected $tags;

	/**
	 * SignsTableSeeder constructor.
	 *
	 * @param $tags
	 */
	public function __construct( \App\Services\TagService $tags ) {
		$this->tags = $tags;
	}


	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Sign::class, 100)->create()->each(function(\App\Sign $sign) {
        	$this->tags->storeTags($sign);
        });
    }
}
