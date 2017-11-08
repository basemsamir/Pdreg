<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConvertToAttributeMedicalUnitVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_unit_visit', function (Blueprint $table) {
            $table->integer('convert_to')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_unit_visit', function (Blueprint $table) {
            $table->dropColumn('convert_to');
        });
    }
}
