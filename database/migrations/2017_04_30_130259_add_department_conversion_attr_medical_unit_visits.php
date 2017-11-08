<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentConversionAttrMedicalUnitVisits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('medical_unit_visit', function (Blueprint $table) {
            //
            $table->boolean('department_conversion')->after('convert_to')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('medical_unit_visit', function (Blueprint $table) {
            //
              $table->dropColumn('department_conversion');
        });
    }
}
