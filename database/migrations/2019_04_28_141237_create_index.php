<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndex extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'signs', function ( Blueprint $table ) {
			$table->index( 'word_id' );
			$table->index( 'deleted_at' );
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'signs', function ( Blueprint $table ) {
			$table->dropIndex( 'signs_word_id_index' );
			$table->dropIndex( 'signs_deleted_at_index' );
		} );
	}
}
