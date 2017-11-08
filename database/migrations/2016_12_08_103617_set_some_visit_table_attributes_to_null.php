<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetSomeVisitTableAttributesToNull extends Migration
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
			$table->string('c_name')->nullable()->change();
			$table->bigInteger('sid')->nullable()->change();
			$table->integer('relation_id')->nullable()->change();
			$table->string('address')->nullable()->change();
			$table->string('job')->nullable()->change();
			$table->string('city')->nullable()->change();
			$table->string('phone_num')->nullable()->change();
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

        });
    }
}
