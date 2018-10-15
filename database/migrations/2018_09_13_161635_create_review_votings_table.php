<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewVotingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_votings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer( 'review_id' )->unsigned()->unique();
            $table->integer( 'user_id' )->unsigned()->unique();
            $table->boolean( 'approve' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_votings');
    }
}
