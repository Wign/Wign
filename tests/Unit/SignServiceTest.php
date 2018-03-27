<?php

namespace Tests\Unit;

use App\Services\SignService;
use App\RequestWord;
use App\Sign;
use App\Word;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SignServiceTest extends TestCase {

	use RefreshDatabase;

	/**
	 * Setup the test environment for SignService (Seeding the database)
	 */
	protected function setUp() {
		parent::setUp();
		factory(Sign::class, 100)->create();

		// Create a special sign with word "testWord"
		factory(\App\Sign::class)->create(['word_id' => function() {
			return factory(\App\Word::class)->create(['word' => 'testWord'])->id;
		}]);
	}

    public function testGetAllSign() {
    	$ss = new SignService();
    	$allsign = $ss->getAllSigns();
    	$this->assertCount(101, $allsign);
    }

    public function testGetSignByWord() {
		$ss = new SignService();
	    $sign = $ss->getSignByWord('testWord')->first();

    	$this->assertNotEmpty($sign);
    	$this->assertNotEmpty($sign->small_thumbnail_url);
    	$this->assertEquals($sign->word->word, 'testWord');
    }
}
