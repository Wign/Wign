<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'words', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->integer( 'language_id' )->unsigned()->unique();
			$table->integer( 'creator_id' )->unsigned();
			$table->string( 'word' )->unique();
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
		Schema::drop( 'words' );
	}

}
