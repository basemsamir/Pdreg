<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            //
			
			$table->string('room_number',50)->after('entry_time')->nullable();
			$table->string('file_number',50)->after('room_number')->nullable();
			$table->integer('cure_type_id')->after('file_number')->nullable();
			$table->string('contract',50)->after('cure_type_id')->nullable();
			$table->integer('exit_status_id')->after('contract')->nullable();
			$table->integer('reference_doctor_id')->after('exit_status_id')->nullable();
			$table->date('exit_date')->after('reference_doctor_id')->nullable();
			
			$table->renamecolumn('city','converted_by_doctor');
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            //
			$table->dropColumn(['room_number', 'file_number', 'cure_type_id','contract','exit_status_id','reference_doctor_id','exit_date']);
			
			$table->renamecolumn('converted_by_doctor','city');
        });
    }
}
