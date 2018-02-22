<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Blacklist extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'blacklist', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'ip' );
			$table->text( 'reason' );
			$table->timestamps();
			$table->softDeletes();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop( 'blacklist' );
	}

}
