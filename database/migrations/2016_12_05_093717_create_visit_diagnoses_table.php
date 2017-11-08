<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitDiagnosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_diagnoses', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('visit_id')->unsigned()->index();
            $table->foreign('visit_id')->references('id')->on('visits');
			$table->text('content');
			$table->integer('typist_id');
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
		Schema::table('visit_diagnoses', function ($table) {
			$table->dropForeign(['visit_id']);
		});
        Schema::drop('visit_diagnoses');
    }
}