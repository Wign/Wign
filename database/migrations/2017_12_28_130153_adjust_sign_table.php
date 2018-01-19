<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustSignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// Changed the default method of created_at and updated at columns
    	DB::unprepared('
			ALTER TABLE signs 
			MODIFY COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
			MODIFY COLUMN updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		');

    	Schema::table('signs', function (Blueprint $table) {
	        $table->text('description')->nullable()->change();
        	$table->string('flag_reason')->nullable()->change();
	        $table->text('flag_comment')->nullable()->change();
	        $table->string('flag_ip')->nullable()->change();
	        $table->string('flag_email')->nullable()->change();
	        $table->integer('plays')->default(0)->change();
        	$table->dropColumn(['camera_uuid', 'recorded_from']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signs', function (Blueprint $table) {
	        $table->string('camera_uuid');
	        $table->string('recorded_from');
        });
    }
}
