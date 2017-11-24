<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('signs', function($table)
    	{
        $table->increments('id')->unique();
        $table->integer('word_id');
        $table->text('description');
        $table->string('video_uuid');
        $table->string('camera_uuid');
        $table->string('recorded_from');
        $table->string('video_url');
        $table->string('thumbnail_url');
        $table->string('small_thumbnail_url');
        $table->integer('plays');
        $table->string('ip');
        $table->string('flag_reason');
        $table->text('flag_comment');
        $table->string('flag_ip');
        $table->string('flag_email');
        $table->timestamps();
        $table->softDeletes();
    	});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('signs');
	}

}
