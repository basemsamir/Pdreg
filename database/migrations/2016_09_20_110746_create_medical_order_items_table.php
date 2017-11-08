<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('visit_id')->unsigned()->index();
            $table->foreign('visit_id')->references('id')->on('visits');
			$table->integer('proc_id');
			$table->integer('doctor_id');
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
		Schema::table('medical_order_items', function ($table) {
			$table->dropForeign(['medical_order_items_visit_id_foreign']);
		});
        Schema::drop('medical_order_items');
    }
}
