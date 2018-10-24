<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemotionVotingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remotion_votings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer( 'remotion_id' )->unsigned();
            $table->integer( 'qcv_id' )->unsigned();
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
        Schema::dropIfExists('remotion_votings');
    }
}
