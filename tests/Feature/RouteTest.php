<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase {

	use RefreshDatabase;

	public function testFront() {
		$response = $this->get( '/' );
		$response->assertStatus(200);
		$response->assertSee('Social tegnsprogsencyklopædi');
	}

	public function testAbout() {
		$response = $this->get('/about');
		$response->assertStatus(200);
		$response->assertSee('Om Wign');
	}

	public function testSign() {
		factory(\App\Sign::class)->create(['word_id' => function() {
			return factory(\App\Word::class)->create(['word' => 'testWord'])->id;
		}]);

		$reponse = $this->get('/sign/testWord');
		$reponse->assertStatus(200);
		$reponse->assertSee('testWord');
	}

	public function testSignWithSpace() {
		factory(\App\Sign::class)->create(['word_id' => function() {
			return factory(\App\Word::class)->create(['word' => 'test a Word'])->id;
		}]);

		$reponse = $this->get('/sign/test_a_Word');
		$reponse->assertStatus(200);
		$reponse->assertSee('test a Word');

		$reponse = $this->get('/sign/test a word');
		$reponse->assertStatus(200);
		$reponse->assertSee('test a Word');
	}
}
