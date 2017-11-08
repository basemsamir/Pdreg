<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetSomePatientsTableAttributesToNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->text('address')->nullable()->change();
            $table->string('issuer')->nullable()->change();
            $table->integer('phone_num')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->string('job')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            //
        });
    }
}
