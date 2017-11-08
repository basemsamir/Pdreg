<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50);
            $table->string('gender',10);
            $table->bigInteger('sid');
            $table->text('address');
            $table->date('birthdate');
            $table->integer('age');
			$table->string('issuer',50);
			$table->integer('phone_num');
			$table->string('nationality',20);
			$table->string('job',50);
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
        Schema::drop('patients');
    }
}
