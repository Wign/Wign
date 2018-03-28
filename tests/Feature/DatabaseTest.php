<?php

namespace Tests\Feature;

use App\RequestWord;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseTest extends TestCase {

	use RefreshDatabase;

	public function testAddRequest() {
		factory( \App\RequestWord::class, 10 )->create();
		factory( \App\RequestWord::class )->create( [ 'ip' => '127.0.0.2' ] );

		$this->assertDatabaseHas('request_words', ['ip' => '127.0.0.2']);
		$this->assertCount(11, RequestWord::all());
	}
}
