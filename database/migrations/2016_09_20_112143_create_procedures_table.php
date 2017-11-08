<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProceduresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned()->index();
			$table->foreign('type_id')->references('id')->on('procedure_types');
			$table->string('name');
            $table->integer('device_id')->nullable();
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
        Schema::table('procedures', function ($table) {
			$table->dropForeign(['procedures_type_id_foreign']);
		});
        Schema::drop('procedures');
    }
}
