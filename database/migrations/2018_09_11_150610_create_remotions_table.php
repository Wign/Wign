<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remotions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer( 'qcv_id' )->unsigned();
            $table->integer('user_id')->unsigned();     // Creator
            $table->boolean( 'promotion' );
            $table->boolean( 'decided' )->default(0);
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
        Schema::dropIfExists('remotions');
    }
}
