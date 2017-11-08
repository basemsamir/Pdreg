<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned()->index();
			$table->foreign('patient_id')->references('id')->on('patients');
			$table->string('c_name');
			$table->bigInteger('sid');
			$table->integer('relation_id')->unsigned()->index();
			$table->foreign('relation_id')->references('id')->on('relations');
			$table->string('address');
			$table->string('job');
			$table->string('city');
			$table->string('phone_num');
			$table->integer('entry_id');
			$table->integer('user_id');
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
        Schema::table('visits', function ($table) {
			$table->dropForeign(['visits_patient_id_foreign']);
			$table->dropForeign(['visits_relation_id_foreign']);
		});

        Schema::drop('visits');
    }
}
