<?php

namespace Tests\Unit;

use App\Services\SignService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignServiceTest extends TestCase {

	use RefreshDatabase;

	protected function setUp(): void {
		parent::setUp();
		// Adding some entries for test classes
		factory(\App\Sign::class, 45)->create();

		factory(\App\Sign::class)->create(['word_id' => function() {
			return factory(\App\Word::class)->create(['word' => 'testWord'])->id;
		}]);

	}

	public function testGetAllSign() {
    	$ss = new SignService();
    	$allsign = $ss->getAllSigns();
    	$this->assertCount(46, $allsign);
    }

    public function testGetSignByWord() {
		$ss = new SignService();
	    $sign = $ss->getSignByWord('testWord')->first();

    	$this->assertNotEmpty($sign);
    	$this->assertNotEmpty($sign->small_thumbnail_url);
    	$this->assertEquals($sign->word->word, 'testWord');
    }
}
