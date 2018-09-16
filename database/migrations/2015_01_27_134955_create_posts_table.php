<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'posts', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->integer( 'word_id' )->unsigned()->unique();
			$table->integer( 'video_id' )->unsigned()->unique();
			$table->integer( 'description_id' )->unsigned()->unique();
			$table->integer( 'language_id')->unsigned();
			$table->smallInteger( 'il' )->unsigned(); //Integrity level
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
		Schema::drop( 'posts' );
	}

}
